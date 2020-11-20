<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Interaction;


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

        /* ROLES */
        $userRole = new Role();
        $userRole->setName('ROLE_USER');
        $manager->persist($userRole);

        $adminRole = new Role();
        $adminRole->setName('ROLE_ADMIN');
        $manager->persist($adminRole);
        $manager->flush();

        /* Users */
        $faker->seed(0);
        $user = new User();
        $user
            ->setFirstname('userfirst')
            ->setLastname('userlast ')
            ->setEmail('user@gmail.com')
            ->setPassword($this->encoder->encodePassword($user, 'user'))
            ->setRole($userRole);
        $manager->persist($user);

        $user = new User();
        $user
            ->setFirstname('userfirst')
            ->setLastname('userlast ')
            ->setEmail('admin@gmail.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
            ->setRole($adminRole);
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
                ->setTitle($faker->text(20))
                ->setSubtitle($faker->realText(30))
                ->setContent($faker->realText(400))
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
