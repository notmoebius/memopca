<?php

namespace App\Form;


use App\Entity\User;
use App\Entity\Memo;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
        ->add('informed', EntityType::class, [
            'class' =>  User::class,
            'multiple' => true,
                             ]);
        $builder->add('inform', EntityType::class, [
            'class' =>  User::class,
            'multiple' => true,
                             ]);
        $builder->add('creer', SubmitType::class)
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Memo::class,
        ]);
    }
}
