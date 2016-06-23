<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 0:43
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ClockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('timezoneUtcOffset', 'text', array(
                'required' => false,
                'error_bubbling' => true,
            ))
            ->add('timezoneName', 'timezone', array(
                'label' => 'Timezone name',
                'required' => false,
                'error_bubbling' => true,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\Clock',
            'cascade_validation' => true,
            'csrf_protection' => false,
            'features' => null,
            'attr' => array(
                'name' => 'configDataForm',
                'class' => 'form',
            )
        ));
    }

    public function getName() {
        return 'clocktype';
    }
}