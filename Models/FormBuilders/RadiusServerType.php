<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 15:36
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use FIT\Bundle\ModuleLinuxBundle\Models\Forms\RadiusServerUdp;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RadiusServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder)
        {
            $data = $event->getData();
            $form = $event->getForm();

            $form
                ->add('name', 'text', array(
                    'label' => "Name",
                    'required' => true,
                    'error_bubbling' => true,
                    'read_only' => true,
                    'attr' => array(
                      //  'name' => "configDataForm[".$data->nameXpath."]",
                    )
                ))
                ->add('udp', new RadiusServerUdpType(), array(
                  //  'features' => (array_key_exists('features', $options) && $options['features'] != null) ? $options['features'] : null,
                ))
                ->add('authenticationType', 'text', array(
                    'label' => "Authentication Type",
                    'required' => false,
                    'error_bubbling' => true,
                    'attr' => array(
                     //   'name' => "configDataForm[".$data->authenticationTypeXpath."]",
                    )
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\RadiusServer',
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
        return 'radiusservertype';
    }
}