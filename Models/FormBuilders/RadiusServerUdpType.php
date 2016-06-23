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

class RadiusServerUdpType extends AbstractType
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
                ->add('authenticationPort', 'integer', array(
                    'label' => "Port",
                    'required' => false,
                    'error_bubbling' => true,
                    'attr' => array(
                    )
                ))
                ->add('sharedSecret', 'text', array(
                    'label' => "Shared secret",
                    'required' => true,
                    'error_bubbling' => true,
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\RadiusServerUdp',
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
        return 'radiusserverudptype';
    }
}