<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $product = new User();
        $product
            ->setFirstname('userfirst')
            ->setLastname('userlast ')
            ->setEmail('axel@gmail.com')
            ->setPassword($this->encoder->encodePassword($product, 'user'))
            ->setRoles(['ROLE_USER']);
        $manager->persist($product);

        $manager->flush();
    }
}
