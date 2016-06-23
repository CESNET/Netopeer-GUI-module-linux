<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 17:20
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AuthenticationUserKeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $form = $event->getForm();

            $form
                ->add('name', 'text', array(
                    'label' => "Key name",
                    'read_only' => false,
                    'required' => true,
                    'error_bubbling' => true,
                ))

                ->add('algorithm', 'text', array(
                    'label' => "Algorithm",
                    'read_only' => false,
                    'required' => true,
                    'error_bubbling' => true,
                ))
                ->add('keyData', 'text', array(
                    'label' => "Key Data",
                    'read_only' => false,
                    'required' => true,
                    'error_bubbling' => true,
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationUserKey',
                'csrf_protection' => false,
                'features' => null,
                'cascade_validation' => true,
                'attr' => array(
                    'name' => 'configDataForm',
                    'class' => 'form',
                )
            )
        );
    }

    public function getName()
    {
        return 'authenticationuserkey';
    }
}