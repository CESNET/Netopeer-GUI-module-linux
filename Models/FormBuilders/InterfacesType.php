<?php

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders\NtpServerType;


class InterfacesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('interface', 'collection', array(
                'type' => new InterfaceInformationType(),
                'allow_add' => true,
                'allow_delete' => true,
                'cascade_validation' => true,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Interfaces',
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
        return 'interfacestype';
    }
}