<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\Interaction;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(0);
        /* Users */
        $user = $this->getReference(UserFixtures::USER_REFERENCE);
        $cat_sport = $this->getReference(UserFixtures::POLITIQUE_CATEGORY);
        $cat_politique = $this->getReference(UserFixtures::SPORT_CATEGORY);

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article
                ->setCreationDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($i % 2 == 0 ? $article::VISIBLE : $article::HIDDEN)
                ->setTitle($faker->text(20))
                ->setSubtitle($faker->realText(30))
                ->setContent($faker->realText(400))
                ->setPicturePath($faker->imageUrl())
                ->setCategory($i % 2 == 0 ? $cat_sport : $cat_politique)
                ->setAutor($user);
            $manager->persist($article);
            $articles[] = $article;
            $manager->flush();
        }


        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment
                ->setContent($faker->realText(500))
                ->setDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($i % 2 == 0 ? $comment::SEND : $comment::VALIDE)
                ->setArticle($articles[$faker->numberBetween(0, count($articles) - 1)])
                ->setUser($user);
            $manager->persist($comment);
            $manager->flush();
        }


        for ($i = 0; $i < 100; $i++) {
            $interaction = new Interaction();
            $interaction
                ->setArticle($articles[$faker->numberBetween(0, count($articles) - 1)])
                ->setUser($user)
                ->setInteractionType($i % 2 == 0 ? $interaction::LIKE : $interaction::SHARE);
            $manager->persist($interaction);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
