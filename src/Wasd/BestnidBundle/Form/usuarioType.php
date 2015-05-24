<?php

namespace Wasd\BestnidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class usuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_name', null, array(
                'label' => 'Nombre de usuario'))
            ->add('nombre')
            ->add('apellido')
            ->add('email','email')
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Las contraseñas deben coincidir',
                'options' => array('label' => 'Contraseña'),
                'required' => true
            ))
            ->add('telefono')
            ->add('tarjeta')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wasd\BestnidBundle\Entity\usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wasd_bestnidbundle_usuario';
    }
}
