<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 3:16
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class OptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $form = $event->getForm();

            $form
                ->add('timeout', 'integer', array(
                    'label' => "Timeout",
                    'required' => false,
                    'error_bubbling' => true,
                    'invalid_message' => 'Timeout must be number'
                ))
                ->add('attempts', 'integer', array(
                    'label' => "Attempts",
                    'required' => false,
                    'error_bubbling' => true,
                    'invalid_message' => 'Attempts must be number'
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Options',
                'attr' => array(
                    'name' => 'configDataForm',
                    'class' => 'form',
                )
            )
        );
    }


    public function getName()
    {
        return 'optionstype';
    }
}