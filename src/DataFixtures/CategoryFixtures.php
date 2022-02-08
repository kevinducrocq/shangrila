<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\CommentFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'name' => 'Entrées',
                'hide' => 0
            ],
            2 => [
                'name' => 'Plats',
                'hide' => 0
            ],
            3 => [
                'name' => 'Desserts',
                'hide' => 0
            ],
            4 => [
                'name' => 'Fast-food',
                'hide' => 0
            ],
            5 => [
                'name' => 'Boissons',
                'hide' => 0
            ],
            6 => [
                'name' => 'Boissons alcolisées',
                'hide' => 0
            ],
            7 => [
                'name' => 'Pizzas',
                'hide' => 0
            ],
        ];
        foreach ($categories as $key => $value) {
            $category = new Category();
            $category->setName($value['name']);
            $category->setHide($value['hide']);
            $manager->persist($category);

            $this->addReference('category_' . $key, $category);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CommentFixtures::class,
        ];
    }
}
