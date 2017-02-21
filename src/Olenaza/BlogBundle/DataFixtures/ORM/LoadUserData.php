<?php

namespace Olenaza\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Olenaza\BlogBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 20; ++$i) {
            $user = new User();
            $user->setUsername("Name$i");
            $user->setEmail("Email[$i]@gmail.com");
            $user->setPassword("Password$i");

            $this->addReference("User$i", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
