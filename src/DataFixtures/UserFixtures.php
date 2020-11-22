<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Category;


class UserFixtures extends Fixture
{
    const ADMIN_USER_REFERENCE = 'admin-user';
    const USER_REFERENCE = 'user';
    const SPORT_CATEGORY = 'sport-cat';
    const POLITIQUE_CATEGORY = 'politique-cat';
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

            ->addUserRole($userRole);
        $manager->persist($user);
        $manager->flush();
        $users[] = $user;
        $userAdmin = new User();
        $userAdmin
            ->setFirstname('adminfirst')
            ->setLastname('adminlast ')
            ->setEmail('admin@gmail.com')
            ->setPassword($this->encoder->encodePassword($user, 'admin'))
            ->addUserRole($adminRole);
        $manager->persist($userAdmin);
        $manager->flush();
        $users[] = $userAdmin;

        $cat_sport = new Category();
        $cat_sport->setName('sport');
        $cat_politique = new Category();
        $cat_politique->setName('politique');

        $manager->persist($cat_politique);
        $manager->persist($cat_sport);
        $this->addReference(self::USER_REFERENCE, $user);
        $this->addReference(self::ADMIN_USER_REFERENCE, $userAdmin);
        $this->addReference(self::SPORT_CATEGORY, $cat_sport);
        $this->addReference(self::POLITIQUE_CATEGORY, $cat_politique);
    }
}
