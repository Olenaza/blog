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

    public function findForPublication()
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = :published')
            ->andWhere('p.forPublication = :forPublication')
            ->andWhere('p.publishedOn <= :today')
            ->setParameter('published', false)
            ->setParameter('forPublication', true)
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $fragment
     *
     * @return \Doctrine\ORM\Query
     */
    public function findByFragment($fragment)
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->where($qb->expr()->andX(
                $qb->expr()->eq('p.published', ':published'),
                $qb->expr()->lte('p.publishedOn', ':today')
            ))
            ->andWhere($qb->expr()->orX(
                $qb->expr()->like('p.title', ':fragment'),
                $qb->expr()->like('p.text', ':fragment')
            ))
            ->setParameters([
                'published' => true,
                'today' => new \DateTime('today'),
                'fragment' => "%$fragment%",
            ]);

        return $qb->getQuery();
    }
}
