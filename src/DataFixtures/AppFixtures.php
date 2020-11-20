<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Interaction;
use Faker\Factory;


class AppFixtures extends Fixture
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
        $user = new User();
        $user
            ->setFirstname('userfirst')
            ->setLastname('userlast ')
            ->setEmail('axel@gmail.com')
            ->setPassword($this->encoder->encodePassword($user, 'user'))
            ->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        $cat_sport = new Category();
        $cat_sport->setName('sport');
        $cat_politique = new Category();
        $cat_politique->setName('politique');

        $manager->persist($cat_politique);
        $manager->persist($cat_sport);

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article
                ->setCreationDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($i % 2 == 0 ? $article::VISIBLE : $article::HIDDEN)
                ->setTitle($faker->text(150))
                ->setSubtitle($faker->realText(250))
                ->setContent($faker->realText(500))
                ->setPicturePath($faker->imageUrl())
                ->setCategory($i % 2 == 0 ? $cat_sport : $cat_politique);
            $manager->persist($article);
            $articles[] = $article;
        }
        $manager->flush();

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment
                ->setContent($faker->realText(500))
                ->setDate($faker->dateTimeBetween('-6 month', '+6 month', 'Europe/Paris'))
                ->setState($i % 2 == 0 ? $comment::SEND : $comment::VALIDE)
                ->setArticle($articles[$faker->numberBetween(0, count($articles) - 1)])
                ->setUser($user);
            $manager->persist($comment);
        }
        $manager->flush();

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
}
