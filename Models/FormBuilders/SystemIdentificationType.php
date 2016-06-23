<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SystemIdentificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('contact', 'text', array(
                'label' => "Contact",
                'required' => false,
                'attr' => array(
                  //  'name' => "configDataForm[contact_--system--xmlns:contact]",
                )
            ))
            ->add('hostname', 'text', array(
                'label' => "Hostname",
                'required' => false,
                'attr' => array(
                  //  'name' => "configDataForm[hostname_--system--xmlns:hostname]",
                )
            ))
            ->add('location', 'text', array(
                'label' => "Location",
                'required' => false,
                'attr' => array(
                  //  'name' => "configDataForm[location_--system--xmlns:location]",
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\SystemIdentification',
                'csrf_protection' => false,
                'attr' => array(
                    'name' => 'configDataForm',
                    'class' => 'form')
            )
        );
    }

    public function getName() {
        return 'systemidentificationtype';
    }
}