<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UdpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $options)
        {
            $form = $event->getForm();

            $form
                ->add('address', 'text', array(
                    'label' => "Address",
                    'required' => true,
                    'error_bubbling' => true,
                ))
                ->add('port', 'integer', array(
                    'label' => "Port",
                    'required' => false,
                    'read_only' => array_key_exists('features', $options) && $options['features'] != null && (array_search('ntp-udp-port', $options['features']) === false),
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Udp',
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
        return 'udptype';
    }
}