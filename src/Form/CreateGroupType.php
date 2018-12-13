<?php

namespace App\Form;

use App\Entity\LearningGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group', GroupType::class, array(
                'data_class' => LearningGroup::class,
            ))
            ->add('participants', CollectionType::class, array(
                'entry_type' => RegisterUserType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false
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
