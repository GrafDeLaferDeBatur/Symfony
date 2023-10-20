<?php

namespace App\DataFixtures;

use App\Entity\Color;
use App\Entity\Example;
use App\Entity\ExampleDep;
use App\Repository\ColorRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class ColorFixtures extends Fixture
{
    CONST colors = ['Brown', 'Green', 'BlueWhite'];
    public function load(ObjectManager $manager): void
    {
        foreach (self::colors as $color){
            $exampleColor = new Color();
            $exampleColor->setColor($color);

            $this->setReference($color, $exampleColor);
            $manager->persist($exampleColor);
        }

        $manager->flush();
    }
}
