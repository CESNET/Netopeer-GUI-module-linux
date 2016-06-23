<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class NtpServerType extends AbstractType
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
                    'error_bubbling' => true,
                    'read_only' => true,
                ))
                ->add('udp', new UdpType(), array(
                    'features' => (array_key_exists('features', $options) && $options['features'] != null) ? $options['features'] : null,
                ))
                ->add('associationType', 'choice', array(
                    'label' => "Association",
                    'choices' => array(
                        'server' => 'server',
                        'peer' => 'peer',
                        'pool' => 'pool'
                    ),
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('iburst', 'text', array(
                    'label' => "IBurst",
                    'required' => false,
                    'error_bubbling' => true,
                ))
                ->add('prefer', 'text', array(
                    'label' => "Prefer",
                    'required' => false,
                    'error_bubbling' => true,
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\NtpServer',
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
        return 'ntpservertype';
    }
}
