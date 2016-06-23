<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use FIT\Bundle\ModuleLinuxBundle\Models\Forms\InterfaceNeighbor;
use FIT\Bundle\ModuleLinuxBundle\Models\Forms\NtpServer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InterfaceAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $form = $event->getForm();

            $form
                ->add('ip', 'text', array(
                    'label' => "Address IP",
                    'required' => true,
                    'error_bubbling' => true,
                ))
                ->add('prefixLength', 'text', array(
                    'label' => "Prefix length",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('netmask', 'text', array(
                    'label' => "Netmask",
                    'required' => false,
                    'error_bubbling' => true,
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\InterfaceAddress',
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
        return 'interfaceaddresstype';
    }
}