<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;


use FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthenticationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('userAuthenticationOrder', 'collection', array(
                'type' => new AuthenticationOrderType(),
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => true,
                'cascade_validation' => true,
                'options'  => array(
                    'features' => $options['features'],
                ),
            ))
            ->add('user', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'error_bubbling' => true,
                'cascade_validation' => true,
                'type' => new AuthenticationUserType(),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Authentication',
            'csrf_protection' => false,
            'features' => null,
            'cascade_validation' => true,
            'attr' => array(
                'name' => 'configDataForm',
                'class' => 'form',
            )
        ));
    }

    public function getName() {
        return 'authenticationtype';
    }
}