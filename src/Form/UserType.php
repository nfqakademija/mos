<?php

namespace App\Form;

use App\Entity\Region;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('name', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('surname', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('username', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('password', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('region', EntityType::class, array(
                'class' => Region::class,
                'choice_label' => 'title',
                'placeholder' => 'Add a region',
                'required' => false
            ))
            ->add('livingAreaType', ChoiceType::class, array(
                'choices' => array(
                    'Miestas' => 'miestas',
                    'Kaimas' => 'kaimas'
                ),
                'placeholder' => 'Add an area type',
                'required' => false
            ))
            ->add('birthDate', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('address', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('phone', TextType::class, array(
                'required' => false,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('email', EmailType::class, array(
                'required' => false,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('gender', ChoiceType::class, array(
                'choices' => array(
                    'Vyras' => 'vyras',
                    'Moteris' => 'moteris'
                ),
                'data' => 'vyras'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
