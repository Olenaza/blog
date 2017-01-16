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
            ->orderBy('p.publishedOn', 'DESC')
            ->setParameter('published', true)
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
            ->innerJoin('p.categories', 'c', 'WITH',  'c.slug = :slug')
            ->setParameters([
                'published' => true,
                'slug' => $slug,
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
            ->innerJoin('p.tags', 't', 'WITH',  't.name = :name')
            ->setParameters([
                'published' => true,
                'name' => $name,
            ])
            ->getQuery();
    }
}
