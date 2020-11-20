<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $categories = ['Fashion', 'Nourriture', 'Voyage', 'Musique', 'Lifestyle', 'Fitness', 'Sport'];
        $categories_object = [];

        foreach ($categories as $category){
            $cat = new Category();
            $cat->setName($category);
            $manager->persist($cat);
            $categories_object[] = $cat;
        }
        $manager->flush();

        foreach($categories_object as $key => $category_object){
            $article = new Article();
            $article
                ->setCreationDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($key % 2 == 0 ? $article::VISIBLE : $article::HIDDEN)
                ->setTitle($faker->text(150))
                ->setSubtitle($faker->realText(250))
                ->setContent($faker->realText(500))
                ->setPicturePath($faker->imageUrl())
                ->setCategory($category_object);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
