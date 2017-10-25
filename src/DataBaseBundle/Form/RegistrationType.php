<?php
// src/AppBundle/Form/RegistrationType.php

namespace DataBaseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fisrtName', null, array('label' => 'form.firstName', 'translation_domain' => 'FOSUserBundle'))
        ->add('lastName', null, array('label' => 'form.lastName', 'translation_domain' => 'FOSUserBundle'))
        ->add('role', ChoiceType::class, array(
            'label' => 'form.role', 'translation_domain' => 'FOSUserBundle',
            'choices'  => array(
                'eleve' => 'eleve',
                'prof' => 'prof',
            ),
            'choice_label' => function ($value, $key, $index) {
                return 'form.choice.'.$key;
            },
            'required' => true,
        ))
        ->add('school', null, array('label' => 'form.school', 'translation_domain' => 'FOSUserBundle'));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
}
