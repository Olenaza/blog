<?php

namespace Olenaza\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Olenaza\BlogBundle\Entity\Tag;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 4; ++$i) {
            $tag = new Tag();
            $tag->setName("Tag$i");

            $this->addReference("Tag$i", $tag);

            $manager->persist($tag);
            $manager->flush();
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
