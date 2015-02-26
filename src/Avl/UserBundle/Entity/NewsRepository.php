<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 14.02.15
 * Time: 23:26
 */
namespace Avl\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class NewsRepository
 * @package Avl\UserBundle\Entity
 */
class NewsRepository extends EntityRepository
{
    /**
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function findAllNewsByQuery($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        // Create query
        return $this->getEntityManager()
            ->createQuery(
                '
                SELECT
                  news
                FROM
                  UserBundle:News news
                LEFT JOIN
                  news.user user
                LEFT JOIN
                  news.category category
                WHERE
                    news.title LIKE :query
                  OR
                    news.body LIKE :query
                  OR
                    (user.username LIKE :query AND news.user IS NOT null)
                  OR
                    (category.name LIKE :query AND news.category IS NOT null)
                ORDER BY
                  news.id
                ASC
            '
            )
            ->setParameter('query', '%'.$query.'%');
    }
}
