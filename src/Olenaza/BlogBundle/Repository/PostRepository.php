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
     * @param $categorySlug
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByCategory($categorySlug)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->innerJoin('p.categories', 'c', 'WITH',  'c.slug = :slug')
            ->setParameters([
                'published' => true,
                'slug' => $categorySlug,
            ])
            ->getQuery();
    }

    /**
     * @param $tagName
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByTag($tagName)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->innerJoin('p.tags', 't', 'WITH',  't.name = :name')
            ->setParameters([
                'published' => true,
                'name' => $tagName,
            ])
            ->getQuery();
    }
}
