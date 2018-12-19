<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, array(
                'data_class' => User::class,
            ))
            ->add('role', ChoiceType::class, array(
                'choices' => array(
                    'Teacher' => User::ROLE_TEACHER,
                    'Supervisor' => User::ROLE_SUPERVISOR
                ),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'attr' => array('novalidate' => 'novalidate'),
        ));
    }
}
