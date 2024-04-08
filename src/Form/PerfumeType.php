<?php

namespace App\Form;

use App\Entity\Favorites;
use App\Entity\Notes;
use App\Entity\OlfactiveFamily;
use App\Entity\Perfume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerfumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('perfume_title',TextType::class, 
        ['label'=> 'Nom du Parfum', 
        'attr'=> array(
            'placeholder'=> 'nom du parfum'
        )])
        ->add('gender',ChoiceType::class, [
            'choices'=>[
                'femme'=> 'femme',
                'homme'=>'homme',
                'unisex'=> 'unisex'
            ],
            'label'=>'Genre'
        ])
        ->add('price', MoneyType::class,
            ['label'=> 'Prix', 
            'attr'=> array(
                'placeholder'=> 'prix en euros'
            )])
            ->add('description',TextType::class,
            ['label'=> 'Description',
            'attr'=> array(
                'placeholder'=> 'inserer une description du parfum'
            )])

            ->add('volume',NumberType::class, [ 
                'attr'=> array(
                'placeholder'=> 'volume de parfum en millilitres'
            )])

            ->add('quantity',NumberType::class, [ 
                'label'=>'qté en stock',
                'attr'=> array(
                'placeholder'=> 'quantité de ce parfum disponible en stock'
            )])

            ->add('brand',TextType::class,
            ['label'=> 'Marque', 
            'attr'=> array(
            'placeholder'=> 'marque du parfum')]
            )
            
            ->add('olfactive_family_id', EntityType::class, [
                'class' => OlfactiveFamily::class,
                'choice_label' => 'familyName',
                'label'=>'Famille olfactive du parfum'
            ])
            ->add('note_id', EntityType::class, [
                'class' => Notes::class,
                'choice_label' => 'noteName',
                'multiple' => true,
                'expanded' => true,
                'label'=>'notes du Parfum'
            ])
            // ->add('favorites', EntityType::class, [
            //     'class' => Favorites::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            ->add('Submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Perfume::class,
        ]);
    }
}
