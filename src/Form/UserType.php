<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 18.11.15
 * Time: 11.40
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname');
    }
}