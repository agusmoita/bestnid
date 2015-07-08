<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wasd\BestnidBundle\Entity\Usuario;
use Wasd\BestnidBundle\Form\UsuarioType;

/**
 * usuario controller.
 *
 * @Route("/intranet/usuario")
 */
class UsuarioController extends Controller
{

    /**
     * Lists all usuario entities.
     *
     * @Route("/", name="usuario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WasdBestnidBundle:Usuario')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new usuario entity.
     *
     * @Route("/create", name="usuario_create")
     * @Method("POST")
     * @Template("WasdBestnidBundle:Usuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Usuario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->setRol('ROLE_USUARIO');
            $entity->setStatus(true);
            $entity->setFechaAlta(new \DateTime());

            $em->persist($entity);
            $em->flush();
            $this->getRequest()->getSession()->getFlashBag()->add('aviso_exito',
                    'Usuario creado correctamente.');
            return $this->redirect($this->generateUrl('default'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a usuario entity.
     *
     * @param usuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Registrarse'));

        return $form;
    }

    /**
     * Displays a form to create a new usuario entity.
     *
     * @Route("/new", name="usuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Usuario();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a usuario entity.
     *
     * @Route("/{id}", name="usuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing usuario entity.
     *
     * @Route("/{id}/edit", name="usuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find usuario entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a usuario entity.
    *
    * @param usuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Usuario $entity)
    {
        $form = $this->createForm(new UsuarioType(), $entity, array(
            'action' => $this->generateUrl('usuario_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing usuario entity.
     *
     * @Route("/{id}", name="usuario_update")
     * @Method("POST")
     * @Template("WasdBestnidBundle:Usuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find usuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('default'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a usuario entity.
     *
     * @Route("/{id}", name="usuario_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find usuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usuario'));
    }

    /**
     * Creates a form to delete a usuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('usuario_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @Route("/admin/user_stats", name="usuarios_estadisticas")
     * @Template()
     */
    public function userStatsAction()
    {
        $request = $this->getRequest();

        $formulario = $this->createFormBuilder()
          ->add('fechaDesde', 'date', array(
                'label' => 'Fecha Desde',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Fecha incorrecta (dd/mm/aaaa)',
                'required' => true
                ))
          ->add('fechaHasta', 'date', array(
                'label' => 'Fecha Hasta',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Fecha incorrecta (dd/mm/aaaa)',
                'required' => false
                ))
          ->getForm();

          if ($request->getMethod() == 'POST'){
            $formulario->bind($request);
            if ($formulario->isValid()){
              $em = $this->getDoctrine()->getManager();
              $d = $formulario->get('fechaDesde')->getData();
              $h = $formulario->get('fechaHasta')->getData();
              $desde = $d->format('Y-m-d');
              if ($h == null){
                $hoy = new \DateTime();
                $hasta = $hoy->format('Y-m-d');
              }
              else{
                $hasta = $h->format('Y-m-d');
              }
              $usuarios = $em->getRepository('WasdBestnidBundle:Usuario')->cantFechas($desde, $hasta);
              $cant = count($usuarios);
              return array(
                'form' => $formulario->createView(),
                'desde' => $d,
                'hasta' => $h,
                'usuarios' => $usuarios,
                'cant' => $cant
              );
            }
          }

          return array(
            'form' => $formulario->createView()
          );
    }

    /**
     * @Route("/admin/products_stats", name="productos_estadisticas")
     * @Template()
     */
    public function prodStatsAction()
    {
        $request = $this->getRequest();

        $formulario = $this->createFormBuilder()
          ->add('fechaDesde', 'date', array(
                'label' => 'Fecha Desde',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Fecha incorrecta (dd/mm/aaaa)',
                'required' => true
                ))
          ->add('fechaHasta', 'date', array(
                'label' => 'Fecha Hasta',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'invalid_message' => 'Fecha incorrecta (dd/mm/aaaa)',
                'required' => false
                ))
          ->getForm();

          if ($request->getMethod() == 'POST'){
            $formulario->bind($request);
            if ($formulario->isValid()){
              $em = $this->getDoctrine()->getManager();
              $d = $formulario->get('fechaDesde')->getData();
              $h = $formulario->get('fechaHasta')->getData();
              $desde = $d->format('Y-m-d');
              if ($h == null){
                $hoy = new \DateTime();
                $hasta = $hoy->format('Y-m-d');
              }
              else{
                $hasta = $h->format('Y-m-d');
              }
              $productos = $em->getRepository('WasdBestnidBundle:Producto')->cantFechas($desde, $hasta);
              $cant = count($productos);
              return array(
                'form' => $formulario->createView(),
                'desde' => $d,
                'hasta' => $h,
                'productos' => $productos,
                'cant' => $cant
              );
            }
          }

          return array(
            'form' => $formulario->createView()
          );
    }

    /**
     * @Route("/{id}/eliminarPerfil", name="eliminar_perfil")
     */
    public function eliminarPerfilAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

        $productos = $usuario->getProductos();
        foreach ($productos as $producto) {
          if ($producto->getOfertaGanadora() == null){
            $this->getRequest()->getSession()->getFlashBag()->add('aviso_error',
                      'No puedes eliminar tu perfil porque tienes subastas pendientes.');
            return $this->redirect($this->generateUrl('default'));  
          }
        }

        $ofertas = $usuario->getOfertas();
        foreach ($ofertas as $oferta) {
          if ($oferta->getProducto()->getOfertaGanadora() == null){
            $this->getRequest()->getSession()->getFlashBag()->add('aviso_error',
                      'No puedes eliminar tu perfil porque tienes ofertas pendientes.');
            return $this->redirect($this->generateUrl('default'));  
          }
        }

        $usuario->setStatus(false);
        $em->persist($usuario);
        $em->flush();
        $this->getRequest()->getSession()->getFlashBag()->add('aviso_exito',
                    'Perfil eliminado correctamente.');
        return $this->redirect($this->generateUrl('usuario_logout'));
    }
}
