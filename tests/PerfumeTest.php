<?php

namespace App\Tests;

use App\Entity\Perfume;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PerfumeTest extends KernelTestCase
{
    // Méthode de test pour l'entité Perfume
    public function testPerfumeEntity(): void
    {
        // Démarrage du noyau Symfony et récupération du conteneur de services Symfony
        $kernel = self::bootKernel();
        $container = static::getContainer();

        // Création d'un nouvel objet Perfume
        $perfume = new Perfume();

        // Définition des propriétés de l'objet Perfume à l'aide des méthodes setters
        $perfume->setPerfumeTitle('PerfumTestTitle')
            ->setDescription('DescriptionTest')
            ->setOlfactiveFamilyId(null)
            ->setPrice(100)
            ->setBrand('BrandTest')
            ->setQuantity(1)
            ->setVolume(50)
            ->setGender('femme');

        // Validation de l'objet Perfume à l'aide du service validator
        $errors = $container->get('validator')->validate($perfume);

        // Vérification qu'il n'y a aucune erreur de validation
        $this->assertCount(0, $errors);
        // Vérification que les valeurs des propriétés perfumeTitle
        // et description sont bien celles qui ont été définies précédemment
        $this->assertSame('PerfumTestTitle', $perfume->getPerfumeTitle());
        $this->assertSame('DescriptionTest', $perfume->getDescription());
    }
}