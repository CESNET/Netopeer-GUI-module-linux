<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AuthenticationUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $form = $event->getForm();

            $form
                ->add('name', 'text', array(
                    'label' => "Name",
                    'required' => true,
                    'error_bubbling' => true,
                    'read_only' => true,
                ))
                ->add('password', 'text', array(
                    'label' => "Password",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('authorizedKey', 'collection', array(
                    'allow_add' => true,
                    'allow_delete' => true,
                    'error_bubbling' => true,
                    'cascade_validation' => true,
                    'type' => new AuthenticationUserKeyType(),
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationUser',
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
        return 'authenticationuser';
    }
}