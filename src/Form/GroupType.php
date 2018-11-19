<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.11.15
 * Time: 11.06
 */

namespace App\Form;


use App\Entity\LearningGroup;
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
            ->add('address', TextType::class)
            ->add('participants', CollectionType::class, array(
                'entry_type' => UserType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Submit'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => LearningGroup::class,
            'attr'=> array('novalidate' => 'novalidate')
        ));
    }
}