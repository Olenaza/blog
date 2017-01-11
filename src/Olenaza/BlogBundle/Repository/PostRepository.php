<?php

namespace Olenaza\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    /**
     * Find all published posts? order them by publishedOn field.
     *
     * @param int $limit
     *
     * @return array
     */
    public function findAllOrderedByPublicationDate($limit = null)
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->orderBy('p.publishedOn', 'DESC')
            ->setParameter('published', true)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
