<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('username', TextType::class)
            ->add('password', TextType::class)
            ->add('birthDate', TextType::class, array(
                'required' => false
            ))
            ->add('address', TextType::class, array(
                'required' => false
            ))
            ->add('phone', TextType::class, array(
                'required' => false
            ))
            ->add('email', EmailType::class, array(
                'required' => false
            ))
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Vyras' => 'vyras',
                    'Moteris' => 'moteris'
                ),
                'data' => 'vyras'
            ))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
