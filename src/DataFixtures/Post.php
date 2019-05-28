<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class Post extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $post = new \App\Entity\Post();
            $post->setTitle($faker->sentence(6, 3));
            $post->setContent($faker->text(200));
            $manager->persist($post);
        }

        $manager->flush();
    }
}
