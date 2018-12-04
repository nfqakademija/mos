<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', TextType::class, [
                'label' => "Date from:",
                'required' => true,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ])
            ->add('dateTo', TextType::class, [
                'label' => "to:",
                'required' => true,
                'attr' => array(
                    'autocomplete' => 'off'
                )
            ])
            ->add('display', SubmitType::class, ['label' => 'Display', 'attr' => ['class' => 'btn']])
            ->add('export', SubmitType::class, ['label' => 'Export', 'attr' => ['class' => 'btn']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
