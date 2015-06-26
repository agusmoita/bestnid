<?php

namespace Wasd\BestnidBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Wasd\BestnidBundle\Entity\Oferta;
use Wasd\BestnidBundle\Form\OfertaType;

/**
 * Oferta controller.
 *
 * @Route("/oferta")
 */
class OfertaController extends Controller
{

    /**
     * Creates a new Oferta entity.
     *
     * @Route("/", name="oferta_create")
     * @Method("POST")
     * @Template("WasdBestnidBundle:Oferta:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Oferta();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $session = $this->getRequest()->getSession();

            $usuario = $em->getRepository('WasdBestnidBundle:Usuario')->find($this->getUser()->getId());
            $producto = $em->getRepository('WasdBestnidBundle:Producto')->find($session->get('id_producto'));

            $validUser = $em->getRepository('WasdBestnidBundle:Oferta')->findUsuarioRepetido($producto, $usuario);

            if ($validUser){
                $this->getRequest()->getSession()->getFlashBag()->add('aviso_error', 
                        'Ya has realizado una oferta por este producto.');
                return $this->redirect($this->generateUrl('producto_show', array('id'=>$producto->getId())));
            }

            $hoy = new \DateTime();
            if ($producto->getFechaFin() < $hoy){
                $this->getRequest()->getSession()->getFlashBag()->add('aviso_error', 
                        'No se pueden realizar más ofertas.');
                return $this->redirect($this->generateUrl('producto_show', array('id'=>$producto->getId())));            
            }

            $entity->setFecha(new \DateTime());
            $entity->setUsuario($usuario);
            $entity->setProducto($producto);

            $em->persist($entity);
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('aviso_exito', 
                    'Oferta realizada con éxito.');
            return $this->redirect($this->generateUrl('producto_show', array('id' => $session->get('id_producto'))));
        }

        return $this->redirect($this->generateUrl('producto_show', array('id' => $session->get('id_producto'))));
    }

    /**
     * Creates a form to create a Oferta entity.
     *
     * @param Oferta $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Oferta $entity)
    {
        $form = $this->createForm(new OfertaType(), $entity, array(
            'action' => $this->generateUrl('oferta_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ofertar'));

        return $form;
    }

    /**
     * Finds and displays a Oferta entity.
     *
     * @Route("/{id}", name="oferta_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Oferta entity.
     *
     * @Route("/{id}/edit", name="oferta_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
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
    * Creates a form to edit a Oferta entity.
    *
    * @param Oferta $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Oferta $entity)
    {
        $form = $this->createForm(new OfertaType(), $entity, array(
            'action' => $this->generateUrl('oferta_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Oferta entity.
     *
     * @Route("/{id}", name="oferta_update")
     * @Method("PUT")
     * @Template("WasdBestnidBundle:Oferta:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WasdBestnidBundle:Oferta')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Oferta entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('oferta_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Oferta entity.
     *
     * @Route("/{id}", name="oferta_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WasdBestnidBundle:Oferta')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Oferta entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('oferta'));
    }

    /**
     * Creates a form to delete a Oferta entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('oferta_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
