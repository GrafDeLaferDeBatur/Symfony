<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\ProductAttribute;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $exampleProductAttr = new ProductAttribute();
            $exampleProductAttr->setWeight(mt_rand(0, 100));
            $exampleProductAttr->setLength(mt_rand(0, 100));
            $exampleProductAttr->setWidth(mt_rand(0, 100));
            $exampleProductAttr->setHeight(mt_rand(10, 100));
            $manager->persist($exampleProductAttr);

            $exampleProduct = new Product();

            $exampleProduct->setTitle('Sugar'.$i);
            $exampleProduct->setDescr('SugarSugarSugar'.$i);
            $exampleProduct->setPrice(mt_rand(1, 100));
            $exampleProduct->setAmount(mt_rand(1, 100));
            $exampleProduct->setColor($this->getReference(ColorFixtures::colors[array_rand(ColorFixtures::colors)]));
            $exampleProduct->setProductAttribute($exampleProductAttr);
            $exampleProduct->setDimensions(mt_rand(1,3));
            $exampleProduct->setCategory($this->getReference(CategoryFixtures::categories[array_rand(CategoryFixtures::categories)]));
            $manager->persist($exampleProduct);
        }

        $manager->flush();
    }
    public static function getDependencies()
    {
        return [
            ColorFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
