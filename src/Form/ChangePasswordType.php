<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, array(
                'label' => 'Old password',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Įveskite dabartinį slaptažodį'
                    )),
                    new UserPassword(array(
                        'message' => 'Neteisingas dabartinis slaptažodis',
                    ))
                ),
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Slaptažodžiai turi sutapti',
                'required' => true,
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                'constraints' => new NotBlank(array(
                    'message' => 'Įveskite slaptažodį'
                ))
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Submit'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array('novalidate' => 'novalidate')
        ));
    }
}
