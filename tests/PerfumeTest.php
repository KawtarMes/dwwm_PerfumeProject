<?php

namespace App\Tests;

use App\Entity\Perfume;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PerfumeTest extends KernelTestCase
{
    public function testPerfumeEntity(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $perfume = new Perfume();

        $perfume->setPerfumeTitle('PerfumTestTitle')
            ->setDescription('DescriptionTest')
            ->setOlfactiveFamilyId(null)
            ->setPrice(100)
            ->setBrand('BrandTest')
            ->setQuantity(1)
            ->setVolume(50)
            ->setGender('femme');

        $errors = $container->get('validator')->validate($perfume);

        $this->assertCount(0, $errors);
        $this->assertSame('PerfumTestTitle', $perfume->getPerfumeTitle());
        $this->assertSame('DescriptionTest', $perfume->getDescription());
    }
}
