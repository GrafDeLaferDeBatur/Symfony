<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Example;
use App\Entity\ExampleDep;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class CategoryFixtures extends Fixture
{
    CONST mainCategories = ['Food', 'NotFood'];
    CONST categories = ['Milk', 'Meat', 'Stone'];
    private array $mCategories = [];
    public function load(ObjectManager $manager): void
    {
        foreach(self::mainCategories as $category){
            $exampleCategory = new Category();
            $exampleCategory->setName($category);
            $this->mCategories[] = $exampleCategory;
            $manager->persist($exampleCategory);

        }
        $manager->flush();

        foreach(self::categories as $category){
            $exampleCategory = new Category();
            $exampleCategory->setCategory($this->mCategories[array_rand($this->mCategories)]);
            $exampleCategory->setName($category);
//            array_push(self::$_categories, $exampleCategory);

            $this->addReference($category, $exampleCategory);

            $manager->persist($exampleCategory);
        }

        $manager->flush();
    }
}
