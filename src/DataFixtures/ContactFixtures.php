<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Contact;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 50; $i++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 15));
            $contact = new Contact();
            $contact->setUser($user);
            $contact->setCreatedAt($faker->dateTime());
            $contact->setMessage($faker->realtext(mt_rand(100, 200)));
            $contact->setStatus($faker->numberBetween(0, 1));
            if ($contact->getStatus(1)) {
                $contact->setAnswer($faker->realtext(mt_rand(100, 200)));
            }
            $manager->persist($contact);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
