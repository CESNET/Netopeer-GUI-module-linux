<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use FIT\Bundle\ModuleLinuxBundle\Models\Forms\NtpServer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class InterfaceInformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $options)
        {
            $form = $event->getForm();

            $form
                ->add('name', 'text', array(
                    'label' => "Name",
                    'required' => true,
                    'read_only' => true,
                    'error_bubbling' => true,
                ))
                ->add('description', 'text', array(
                    'label' => "Description",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('type', 'text', array(
                    'label' => "Type",
                    'required' => true,
                    'error_bubbling' => true,
                ))
                ->add('enabled', 'text', array(
                    'label' => "Enabled",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('linkUpDownTrapEnable', 'text', array(
                    'label' => "Link Up Down Trap Enable",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('ipv4', new InterfaceIpv4Type(), array());
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\InterfaceInformation',
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
        return 'interfaceinformationtype';
    }
}