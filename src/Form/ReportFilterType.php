<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'label' =>"Date from:",
                'required' => true,
                'data' => new \DateTime('first day of this month')
            ])
            ->add('dateTo', DateType::class, [
                'widget' => 'single_text',
                'label' =>"to:",
                'required' => true,
                'data' => new \DateTime('last day of this month')
                ])
          ->add('display', SubmitType::class, ['label' => 'Display', 'attr' =>['class' => 'btn']])
          ->add('export', SubmitType::class, ['label' => 'Export', 'attr' =>['class' => 'btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
