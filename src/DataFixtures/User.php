<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;


    /**
     * User constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new \App\Entity\User();
            $user
                ->setEmail("injection$i@email.com")
                ->setPassword($this->encoder->encodePassword($user, "1234"));
            $manager->persist($user);
            $this->addReference(\App\Entity\User::class . '_' . $i, $user);
        }
        $manager->flush();
    }
}
