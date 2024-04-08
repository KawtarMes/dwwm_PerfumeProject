<?php

namespace App\Form;

use App\Entity\Favorites;
use App\Entity\Notes;
use App\Entity\OlfactiveFamily;
use App\Entity\Perfume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NotesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('noteName', TextType::class, [
                'label'=> "Note"
            ])
            // ->add('perfumes', EntityType::class, [
            //     'class' => Perfume::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('olfactive_family_id', EntityType::class, [
            //     'class' => OlfactiveFamily::class,
            //     'label' => "Famille olfactive",
            //     'choice_label' => 'familyName', // choix de la propriété que je veux selectionnée
            //     'multiple' => true,
            // ])
            // ->add('favorites', EntityType::class, [
            //     'class' => Favorites::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            ->add('Submit', SubmitType::class, [
                'label'=> "Enregister"
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Notes::class,
        ]);
    }
}
