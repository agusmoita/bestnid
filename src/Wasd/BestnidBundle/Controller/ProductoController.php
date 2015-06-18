<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wasd\BestnidBundle\Entity\Producto;
use Wasd\BestnidBundle\Form\ProductoType;

/**
 * Producto controller.
 *
 * @Route("/intranet/producto")
 */
class ProductoController extends Controller
{

    /**
     * Creates a new Producto entity.
     *
     * @Route("/", name="producto_create")
     * @Method("POST")
     * @Template("WasdBestnidBundle:Producto:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Producto();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $session = $this->getRequest()->getSession();

            $plazo = $form->getData()->getVencimiento();
            $fechaAlta = new \DateTime();
            $fechaFin = new \DateTime();
            date_modify($fechaFin, "+".$plazo." days");

            $id = $this->getUser()->getId();
            $usuario = $em->getRepository('WasdBestnidBundle:Usuario')->find($id);

            $entity->subirFoto($this->container->getParameter('bestnid.directorio.imagenes'));
            $entity->setFechaAlta($fechaAlta);
            $entity->setFechaFin($fechaFin);
            $entity->setUsuario($usuario);

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('producto_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Producto entity.
     *
     * @param Producto $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
            'action' => $this->generateUrl('producto_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Subastar'));

        return $form;
    }

    /**
     * Displays a form to create a new Producto entity.
     *
     * @Route("/new", name="producto_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Producto();
        $entity->setVencimiento(15);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Producto entity.
     *
     * @Route("/{id}/edit", name="producto_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Producto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        if ($this->getUser()->getId() != $entity->getUsuario()->getId()){

            $this->getRequest()->getSession()->getFlashBag()->add('aviso_error', 
                    'No puedes modificar este producto.');
            return $this->redirect($this->generateUrl('producto_show', array('id'=>$id)));
        }

        if (count($entity->getOfertas()) > 0 ){
            $this->getRequest()->getSession()->getFlashBag()->add('aviso_error', 
                    'No puedes modificar este producto.');
            return $this->redirect($this->generateUrl('producto_show', array('id'=>$id)));
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
    * Creates a form to edit a Producto entity.
    *
    * @param Producto $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Producto $entity)
    {
        $form = $this->createForm(new ProductoType(), $entity, array(
            'action' => $this->generateUrl('producto_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Guardar'));

        return $form;
    }
    /**
     * Edits an existing Producto entity.
     *
     * @Route("/{id}", name="producto_update")
     * @Method("POST")
     * @Template("WasdBestnidBundle:Producto:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Producto')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Producto entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $rutaOriginal = $entity->getRutaFoto();

        if ($editForm->isValid()) {

            $plazo = $editForm->getData()->getVencimiento();
            $fechaAlta = $entity->getFechaAlta();
            $fechaFin = $fechaAlta;
            date_modify($fechaFin, "+".$plazo." days");

            if ($editForm->getData()->getFoto() != null){
                $entity->subirFoto($this->container->getParameter('bestnid.directorio.imagenes'));
            }

            $entity->setFechaFin($fechaFin);

            $em->flush();

            return $this->redirect($this->generateUrl('producto_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Producto entity.
     *
     * @Route("/{id}/delete", name="producto_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WasdBestnidBundle:Producto')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Producto entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('default'));
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
            ->add('submit', 'submit', array('label' => 'Eliminar'))
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/{oi}/seleccionar_oferta", name="seleccionar_oferta")
     */
    public function seleccionarAction($id, $oi)
    {
        $em = $this->getDoctrine()->getManager();

        $producto = $em->getRepository('WasdBestnidBundle:Producto')->find($id);
        $oferta = $em->getRepository('WasdBestnidBundle:Oferta')->find($oi);

        $hoy = new \DateTime();
        if ($producto->getFechaFin() > $hoy){
            $this->getRequest()->getSession()->getFlashBag()->add('aviso_error', 
                    'Todavía no puedes elegir un ganador.');
            return $this->redirect($this->generateUrl('producto_show', array('id'=>$id)));            
        }

        $producto->setOfertaGanadora($oferta);

        $em->persist($producto);
        $em->flush();

        $this->getRequest()->getSession()->getFlashBag()->add('aviso_exito', 
                    'Oferta seleccionada con éxito.');
        return $this->redirect($this->generateUrl('producto_show', array('id'=>$id)));
    }
}
