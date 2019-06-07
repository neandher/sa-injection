<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class User extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 10; $i++){
            $user = new \App\Entity\User();
            $user
                ->setEmail("injection$i@email.com")
                ->setPassword( "1234")
            ;
            $manager->persist($user);
            $this->addReference(\App\Entity\User::class . '_' . $i, $user);
        }
        $manager->flush();
    }
}
