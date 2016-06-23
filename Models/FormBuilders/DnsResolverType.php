<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DnsResolverType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('search', 'collection', array(
                'type' => new DnsResolverSearchType(),
                'allow_add' => true,
                'allow_delete' => true,
                'cascade_validation' => true,
            ))
            ->add('server', 'collection', array(
                'type' => new DnsResolverServerType(),
                'allow_add' => true,
                'allow_delete' => true,
                'cascade_validation' => true,
                'options'  => array(
                    'features' => $options['features'],
                )
            ))
            ->add('options', new OptionsType(), array());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\DnsResolver',
            'features' => null,
            'csrf_protection' => false,
            'cascade_validation' => true,
            'attr' => array(
                'name' => 'configDataForm',
                'class' => 'form',
            )
        ));
    }

    public function getName() {
        return 'dnsresolvertype';
    }
}