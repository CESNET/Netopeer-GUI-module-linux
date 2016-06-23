<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 15:36
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class RadiusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('server', 'collection', array(
                'type' => new RadiusServerType(),
                'allow_add' => true,
                'allow_delete' => true,
                'cascade_validation' => true,
            ))
            ->add('options', new OptionsType(), array());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Radius',
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
        return 'radiustype';
    }
}