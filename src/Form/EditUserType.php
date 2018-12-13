<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', UserType::class, array(
                'data_class' => User::class,
            ))
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $user = $event->getData();
                $password = $event->getForm()->getData()->getPassword();
                $user['user']['password'] = $password;
                $event->setData($user);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'attr' => array('novalidate' => 'novalidate'),
        ));
    }
}
