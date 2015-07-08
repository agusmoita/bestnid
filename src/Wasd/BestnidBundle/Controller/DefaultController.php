<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use Wasd\BestnidBundle\Entity\Oferta;
use Wasd\BestnidBundle\Form\OfertaType;
use Wasd\BestnidBundle\Entity\Pregunta;
use Wasd\BestnidBundle\Form\PreguntaType;
use Wasd\BestnidBundle\Entity\Respuesta;
use Wasd\BestnidBundle\Form\RespuestaType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $f = new \DateTime();
        $fecha = $f->format('Y-m-d');

        $entities = $em->getRepository('WasdBestnidBundle:Producto')->buscarVigentes($fecha);

        $deleteForm = $this->createDeleteForm(-1);


        return array(
            'entities' => $entities,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Lists all Categoria entities.
     *
     * @Route("/categorias", name="categoria")
     * @Method("GET")
     * @Template("WasdBestnidBundle:Categoria:index.html.twig")
     */
    public function indexCategoriasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WasdBestnidBundle:Categoria')->findAll();

        $deleteForm = $this->createDeleteCatForm(-1);

        return array(
            'entities' => $entities,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a Categoria entity.
     *
     * @Route("/categoria/{id}", name="categoria_show")
     * @Method("GET")
     * @Template("WasdBestnidBundle:Default:index.html.twig")
     */
    public function showCategoriaAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WasdBestnidBundle:Producto')->buscarPorCategoria($id);

        $deleteForm = $this->createDeleteForm(-1);

        return array(
            'entities'      => $entities,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to delete a Categoria entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteCatForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categoria_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Realizar'))
            ->getForm()
        ;
    }

    /**
     * Finds and displays a Producto entity.
     *
     * @Route("/producto/{id}", name="producto_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Producto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        $session = $this->getRequest()->getSession();
        $session->set('id_producto', $entity->getId());

        if ($this->getUser() == null){
            return array(
                'entity'      => $entity,
                'preguntas' =>$entity->getPreguntas()
            );
        }

        if ($this->getUser()->getId() == $entity->getUsuario()->getId()){

            $forms_res = array();
            foreach ($entity->getPreguntas() as $p) {
                $res = new Respuesta();
                $f = $this->createForm(new RespuestaType(), $res, array('em' => $em))->createView();
                $forms_res[] = $f;
            }
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'entity'    => $entity,
                'ofertas'   => $entity->getOfertas(),
                'preguntas' => $entity->getPreguntas(),
                'formularios' => $forms_res,
                'delete_form' => $deleteForm->createView()
            );
        }

        $usuario = $em->getRepository('WasdBestnidBundle:Usuario')->find($this->getUser()->getId());

        $invalidUser = $em->getRepository('WasdBestnidBundle:Oferta')->findUsuarioRepetido($entity, $usuario);

        $valido = true;
        if ($invalidUser){
            $valido = false;
        }

        $oferta = new Oferta();
        $oferta_form = $this->createForm(new OfertaType(), $oferta);
        $pregunta = new Pregunta();
        $pregunta_form = $this->createForm(new PreguntaType(),$pregunta);
        return array(
            'entity'      => $entity,
            'preguntas' =>$entity->getPreguntas(),
            'oferta_form' => $oferta_form->createView(),
            'pregunta_form' => $pregunta_form->createView(),
            'valido' => $valido,
        );
    }


    /**
     *
     * @Route("/login", name="usuario_login")
     * @Route("/login_check", name="usuario_login_check")
     * @Template("WasdBestnidBundle:Usuario:login.html.twig")
     */
    public function loginAction()
    {
        $peticion =  $this->getRequest();
        $sesion = $peticion->getSession();

        if ($peticion->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $sesion->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $sesion->get(SecurityContext::LAST_USERNAME),
            'error' => $error
        );
    }

    /**
     * Creates a form to delete a Producto entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('producto_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Realizar'))
            ->getForm()
        ;
    }

}
