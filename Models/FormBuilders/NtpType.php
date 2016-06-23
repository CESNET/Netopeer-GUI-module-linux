<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class NtpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('enabled', 'hidden', array(
                'label' => "Enabled",
                'required' => false,
                'error_bubbling' => true,
            ))
            ->add('server', 'collection', array(
                'type' => new NtpServerType(),
                'allow_add' => true,
                'allow_delete' => true,
                'cascade_validation' => true,
                'options'  => array(
                    'features' => $options['features'],
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Ntp',
            'csrf_protection' => false,
            'features' => null,
            'cascade_validation' => true,
            'attr' => array(
                'name' => 'configDataForm',
                'class' => 'form',
            )
        ));
    }

    public function getName()
    {
        return 'ntptype';
    }
}
