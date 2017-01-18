<?php

namespace Olenaza\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Olenaza\BlogBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 20; ++$i) {
            $post = new Post();
            $post->setTitle("Post$i Title");
            $post->setSubtitle("Post$i Subtitle");
            $post->setBeginning("Post$i Beginning");
            $post->setText("Post$i Text");
            $post->setCoverImage("https://www.$i.net");
            $post->setPublished(true);
            $post->setPublishedOn(new \DateTime());

            $number = $i % 5;
            $post->addTag($this->getReference("Tag$number"));
            $post->addCategory($this->getReference("Category$number"));

            $this->addReference("Post$i", $post);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}
