<?php

namespace Olenaza\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Olenaza\BlogBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parentCategory1 = new Category();
        $parentCategory1->setTitle('ParentCategory1');
        $manager->persist($parentCategory1);
        $parentCategory2 = new Category();
        $parentCategory2->setTitle('ParentCategory2');
        $manager->persist($parentCategory2);

        for ($i = 0; $i <= 4; ++$i) {
            $category = new Category();
            $category->setTitle("Category$i");

            if ($i % 2 == 0) {
                $category->setParent($parentCategory1);
            } else {
                $category->setParent($parentCategory2);
            }

            $this->addReference("Category$i", $category);

            $manager->persist($category);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}
