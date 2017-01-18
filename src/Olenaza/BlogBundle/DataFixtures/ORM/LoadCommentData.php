<?php

namespace Olenaza\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Olenaza\BlogBundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 20; ++$i) {
            $comment = new Comment($this->getReference("Post$i"));
            $comment->setText('Text');

            $manager->persist($comment);
            $manager->flush();
        }
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }
}
