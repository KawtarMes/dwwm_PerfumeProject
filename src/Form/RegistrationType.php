<?php

namespace App\Form;

use App\Entity\Rating;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname')
            ->add('firstname')
            ->add('lastname')
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type'=> PasswordType::class,
                'required'=> true,
                'first_options'=> ['label'=> 'Mot de passe'],
                'second_options'=> ['label'=> 'Confirmation du mot de passe'],
                'invalid_message'=> 'Les mots de passe doivent correspondre',
                'constraints'=> [new Length([
                    'min'=> 3,
                    'minMessage' => 'Pas moins de {{ limit }} caratères',
                ])]])
            // ->add('active')
            // ->add('token')
            // ->add('roles')
            // ->add('rating', EntityType::class, [
            //     'class' => Rating::class,
            //     'choice_label' => 'id',
            // ])
            ->add("submit", SubmitType::class, ['label'=>"S'inscrire"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
