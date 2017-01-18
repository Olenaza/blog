<?php

namespace Olenaza\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    /**
     * @param int $limit
     *
     * @return \Doctrine\ORM\Query
     */
    public function findAllOrderedByPublicationDate($limit = null)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->andWhere('p.publishedOn <= :today')
            ->orderBy('p.publishedOn', 'DESC')
            ->setParameter('published', true)
            ->setParameter('today', new \DateTime('today'))
            ->setMaxResults($limit)
            ->getQuery();
    }

    /**
     * @param $slug
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByCategory($slug)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->andWhere('p.publishedOn <= :today')
            ->innerJoin('p.categories', 'c', 'WITH',  'c.slug = :slug')
            ->setParameters([
                'published' => true,
                'slug' => $slug,
                'today' => new \DateTime('today'),
            ])
            ->getQuery();
    }

    /**
     * @param $name
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByTag($name)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->andWhere('p.publishedOn <= :today')
            ->innerJoin('p.tags', 't', 'WITH',  't.name = :name')
            ->setParameters([
                'published' => true,
                'name' => $name,
                'today' => new \DateTime('today'),
            ])
            ->getQuery();
    }
}
