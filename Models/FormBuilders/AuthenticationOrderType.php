<?php
/**
 * Created by PhpStorm.
 * User: Honza
 * Date: 12.6.2016
 * Time: 12:51
 */

namespace FIT\Bundle\ModuleLinuxBundle\Models\FormBuilders;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AuthenticationOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($builder, $options)
        {
            $form = $event->getForm();
            $orderChoices = array();
            if (!(array_key_exists('features', $options) && $options['features'] != null && (array_search('local-users', $options['features']) === false))) {
                $orderChoices['local-users'] = 'local-users';
            }
            if (!(array_key_exists('features', $options) && $options['features'] != null && (array_search('radius-authentication', $options['features']) === false))) {
                $orderChoices['radius'] = 'radius';
            }

            $form
                ->add('userAuthenticationOrder', 'choice', array(
                    'label' => "Order",
                    'required' => false,
                    'error_bubbling' => true,
                    'choices' => $orderChoices,
                ));
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'FIT\Bundle\ModuleLinuxBundle\Models\Forms\AuthenticationOrder',
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
        return 'authenticationorder';
    }
}