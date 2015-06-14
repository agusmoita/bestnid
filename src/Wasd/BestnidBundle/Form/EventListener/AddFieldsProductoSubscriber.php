<?php

namespace Wasd\BestnidBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormFactoryInterface;

class AddFieldsProductoSubscriber implements EventSubscriberInterface
{
    private $factory;
 
    public function __construct(FormFactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public static function getSubscribedEvents()
    {
        // Informa al despachador que deseas escuchar el evento
        // form.pre_set_data y se debe llamar al mÃ©todo 'preSetData'.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        // pregunto si estoy en el new
        if (!$data || !$data->getId()) {
            $form->add($this->factory->createNamed('foto', 'file', null, array(
                'label' => 'Foto',
                'auto_initialize' => false,
                'required' => true,
                )));
        }
        else{
            $form->add($this->factory->createNamed('foto', 'file', null, array(
                'label' => 'Foto',
                'auto_initialize' => false,
                'required' => false,
                )));
        }
    }
}

