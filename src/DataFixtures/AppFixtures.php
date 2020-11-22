<?php

namespace App\DataFixtures;

use App\DataFixtures\ArticleFixtures;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
    }
    public function getDependencies()
    {
        return array(
            ArticleFixtures::class,
            CategoryFixtures::class,
        );
    }
}
