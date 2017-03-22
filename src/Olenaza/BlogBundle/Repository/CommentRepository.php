<?php

namespace Olenaza\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    /**
     * @param $slug
     *
     * @return array
     */
    public function findAllByPost($slug)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.post', 'p', 'WITH',  'p.slug = :slug')
            ->orderBy('c.publishedAt', 'DESC')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getResult();
    }
}
