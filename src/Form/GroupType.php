<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.11.15
 * Time: 11.06
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address')
            ->add('participants', CollectionType::class, array(
                'entry_type' => UserType::class,
                "allow_add" => true,
                'label' => false
            ))
            ->add('save', SubmitType::class)
        ;
    }
}