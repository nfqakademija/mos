<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.11.15
 * Time: 11.40
 */

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
            ->add('name')
            ->add('surname')
            ->add('username')
            ->add('password')
            ->add('birthDate', TextType::class, array(
                'required' => false
            ))
            ->add('address', null, array(
                'required' => false
            ))
            ->add('phone',null, array(
                'required' => false
            ))
            ->add('email',EmailType::class, array(
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