<?php

namespace Blogger\BlogBundle\Repository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatest($limit, $offset) {
        $queryBuilder = $this->createQueryBuilder('post');

        $queryBuilder->orderBy('post.created', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();

        return $query->getResult();
    }
}
