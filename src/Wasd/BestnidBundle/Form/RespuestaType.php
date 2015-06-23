<?php

namespace Wasd\BestnidBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Wasd\BestnidBundle\Form\DataTransformer\EntityToIntTransformer;

class RespuestaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $PreguntaToIntTransformer = new EntityToIntTransformer($options["em"]);
        $PreguntaToIntTransformer->setEntityClass(
                "Wasd\BestnidBundle\Entity\Pregunta");
        $PreguntaToIntTransformer->setEntityRepository(
                "WasdBestnidBundle:Pregunta");
        $PreguntaToIntTransformer->setEntityType("Pregunta");

        $builder
            ->add('descripcion')
            ->add($builder->create('pregunta', 'hidden')
                    ->addModelTransformer($PreguntaToIntTransformer))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Wasd\BestnidBundle\Entity\Respuesta'
        ));
        $resolver->setRequired(array(
            'em',
        ));
        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'wasd_bestnidbundle_respuesta';
    }
}
