<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $encoder;
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 15; $i++) {
            $faker = Factory::create('fr_FR');
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setEmail($faker->safeEmail);
            $user->setPassword($faker->safeEmail);
            $pwd = $this->encoder->hashPassword($user, '123456');
            $user->setPassword($pwd);
            $user->setAddress($faker->streetAddress);
            $user->setZipcode($faker->postCode);
            $user->setCity($faker->city);
            $manager->persist($user);

            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();
    }
}
