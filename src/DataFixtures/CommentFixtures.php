<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 1; $i < 50; $i++) {
            $user = $this->getReference('user_' . $faker->numberBetween(1, 15));
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setCreatedAt($faker->dateTime());
            $comment->setRating($faker->numberBetween(1, 5));
            $comment->setContent($faker->realtext(mt_rand(50, 150)));
            $comment->setStatus($faker->numberBetween(0, 1));
            $manager->persist($comment);
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
