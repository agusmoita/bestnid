<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\SecurityContext;
use Wasd\BestnidBundle\Entity\Oferta;
use Wasd\BestnidBundle\Form\OfertaType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WasdBestnidBundle:Producto')->findAll();

        return array(
            'entities' => $entities,
        );
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
            );
        }

        if ($this->getUser()->getId() == $entity->getUsuario()->getId()){
            return array(
                'entity'      => $entity,
                'ofertas'     => $entity->getOfertas(),
            );
        }

        $oferta = new Oferta();
        $oferta_form = $this->createForm(new OfertaType(), $oferta);

        return array(
            'entity'      => $entity,
            'oferta_form' => $oferta_form->createView(),
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
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
