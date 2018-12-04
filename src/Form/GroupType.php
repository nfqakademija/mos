<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.11.15
 * Time: 11.06
 */

namespace App\Form;

use App\Entity\LearningGroup;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address', TextType::class, array(
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ))
            ->add('teacher', EntityType::class, array(
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE :val')
                        ->setParameter('val', '%"ROLE_TEACHER"%')
                        ->orderBy('u.id', 'ASC');
                },
                'choice_label' => function (User $user) {
                    return $user->getName() . ' ' . $user->getSurname();
                },
                'placeholder' => 'Choose a teacher'
            ))
            ->add('timeSlots', CollectionType::class, array(
                'entry_type' => TimeSlotType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false
            ))
            ->add('participants', CollectionType::class, array(
                'entry_type' => UserType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Submit'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LearningGroup::class,
            'attr' => array('novalidate' => 'novalidate')
        ));
    }
}
