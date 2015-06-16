<?php

namespace Wasd\BestnidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Wasd\BestnidBundle\Form\EventListener\AddFieldsProductoSubscriber;

class ProductoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo')
            ->add('descripcion','textarea', array(
                'label' => 'Descripción'
            ))
            ->add('vencimiento', null, array(
                'attr' => array('min' => 15, 'max' => 30)
                ))
            ->add('categoria', null, array(
                'empty_value' => '--Seleccione--',
                'required' => true,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                                ->orderBy('c.nombre', 'ASC');
                }
                ))
        ;
        $builder->addEventSubscriber(new AddFieldsProductoSubscriber($builder->getFormFactory()));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wasd\BestnidBundle\Entity\Producto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wasd_bestnidbundle_producto';
    }
}
