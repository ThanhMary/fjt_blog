<?php

namespace App\DataFixtures;

<<<<<<< HEAD
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
=======
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
>>>>>>> 1273ea8d19d9b6911165f3266156ca443577d125

        $manager->flush();
    }
}
