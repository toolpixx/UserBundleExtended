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
    public function getAllNewsByQuery($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        // Create query
        return $this->getEntityManager()
            ->createQuery('
                SELECT news
                FROM UserBundle:News news
                LEFT JOIN news.user user
                LEFT JOIN news.category category
                WHERE news.title LIKE :query
                  OR news.body LIKE :query
                  OR (user.username LIKE :query AND news.user IS NOT null)
                  OR (category.name LIKE :query AND news.category IS NOT null)
                ORDER BY news.createdDate DESC
            ')
            ->setParameter('query', '%'.$query.'%');
    }

    /**
     * @param $slug
     * @return array
     */
    public function getAllInternalNewsFromCategorysBySlug($slug)
    {
        // Create query
        return $this->getEntityManager()
            ->createQuery('
                SELECT news
                FROM UserBundle:News news
                LEFT JOIN news.category category
                WHERE category.path LIKE :slug
                  and news.enabled = TRUE
                  and news.internal = true
                ORDER BY news.createdDate DESC
            ')
            ->setParameter('slug', '%'.$slug.'%')->getResult();
    }

    public function findInteralEnabledNews()
    {
        // Create query
        return $this->getEntityManager()
            ->createQuery('
                SELECT news
                FROM UserBundle:News news
                LEFT JOIN news.category category
                WHERE news.enabled = TRUE
                  AND news.internal = TRUE
                  AND ((news.enabledExpiredDate = FALSE OR news.enabledExpiredDate IS NULL)
                      AND news.enabledDate <= :dateSelect)
                  OR (news.enabledExpiredDate = TRUE AND news.expiredDate >= :dateSelect)
                ORDER BY news.createdDate DESC
            ')->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)->getResult();
    }
}
