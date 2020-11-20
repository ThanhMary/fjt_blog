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
        $categories = ['Fashion', 'Nourriture','Voyage', 'Musique', 'Lifestyle','Fitness','Sport'];
        $ids = [];

        foreach ($categories as $category){
            $cat = new Category();
            $cat->setName($category);
            $manager->persist($cat);
            $manager->flush();
            $ids[] = $cat->getId();
        }

        foreach($ids as $id){
            $article = new Article();
            $article
                ->setCreationDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($id % 2 == 0 ? $article::VISIBLE : $article::HIDDEN)
                ->setTitle($faker->text(150))
                ->setSubtitle($faker->realText(250))
                ->setContent($faker->realText(500))
                ->setPicturePath($faker->imageUrl())
                ->setCategory($id);
            $manager->persist($article);
        }
        $manager->flush();
    }
}
