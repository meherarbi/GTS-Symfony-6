<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class TagsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 10; $i++) {
            $tags = new Tags();
            $tags->setName($faker->word());
            $manager->persist($tags);
        }

        $manager->flush();
    }
    /**
     */
    public function __construct()
    {
    }
}
