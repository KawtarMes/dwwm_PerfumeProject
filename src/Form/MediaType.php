<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Media;
use App\Entity\Perfume;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('title')
            ->add('src', FileType::class,[
                'required'=>'false',
                'label'=>'fichier photo du produit/note/article',
                'attr'=>[
                    'onChange'=>'loadFile(event)'
                ],
                'constraints'=>[ new File([
                    'maxSize'=>'2000k',
                    'maxSizeMessage'=>'Fichier trop volumineux, 2MO maximum',
                    'mimeTypes'=>['image/jpg', 'image/jpeg', 'image/png', 'image/webp'],
                    'mimeTypesMessage'=>"Format autorisés : 'image/jpg', 'image/jpeg', 'image/png', 'image/webp'",
                ])]


                ])
                ->add('Ajouter', SubmitType::class);
                
        //     ->add('perfume', EntityType::class, [
        //         'class' => Perfume::class,
        //         'choice_label' => 'id',
        //     ])
        //     ->add('article', EntityType::class, [
        //         'class' => Article::class,
        //         'choice_label' => 'id',
        //     ])
        // ;
      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}
