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

class InterfaceIpv4Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $form = $event->getForm();

            $form
                ->add('enabled', 'text', array(
                    'label' => "Ipv4 enabled",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('forwarding', 'text', array(
                    'label' => "Forwarding",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('mtu', 'text', array(
                    'label' => "Mtu",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('address', new InterfaceAddressType(), array())
              /*  ->add('neighbor', new InterfaceNeighborType(), array())*/;
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\InterfaceIpv4',
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
        return 'interfaceipv4type';
    }
}