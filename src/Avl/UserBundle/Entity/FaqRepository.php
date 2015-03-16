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
 * Class FaqRepository
 * @package Avl\UserBundle\Entity
 */
class FaqRepository extends EntityRepository
{
    /**
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function getAllFaqByQuery($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        return $this->getEntityManager()
            ->createQuery('
                SELECT faq
                FROM UserBundle:Faq faq
                LEFT JOIN faq.user user
                LEFT JOIN faq.category category
                WHERE faq.question LIKE :query
                  OR faq.answer LIKE :query
                  OR (user.username LIKE :query AND faq.user IS NOT NULL)
                  OR (category.name LIKE :query AND faq.category IS NOT NULL)
                ORDER BY faq.createdDate DESC
            ')
            ->setParameter('query', '%'.$query.'%');
    }

    /**
     * @param $slug
     * @return array
     */
    public function getAllInternalFaqFromCategorysBySlug($slug, $formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        return $this->getEntityManager()
            ->createQuery('
                SELECT faq
                FROM UserBundle:Faq faq
                LEFT JOIN faq.category category
                WHERE category.path LIKE :slug
                  AND category.enabled = TRUE
                  AND category.internal = TRUE
                  AND faq.enabled = TRUE
                  AND faq.internal = TRUE
                  AND (((faq.enabledExpiredDate = FALSE OR faq.enabledExpiredDate IS NULL)
                      AND faq.enabledDate <= :dateSelect)
                  OR (faq.enabledExpiredDate = TRUE AND faq.expiredDate >= :dateSelect))
                  AND (faq.question LIKE :query OR faq.answer LIKE :query)
                ORDER BY faq.createdDate DESC
            ')
            ->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('slug', '%'.$slug.'%')
            ->setParameter('query', '%'.$query.'%')
            ->getResult();
    }

    /**
     * @return array
     */
    public function getAllFaqWhereEnabledAndInternalBySlug($slug)
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT faq, category
                FROM UserBundle:Faq faq
                LEFT JOIN faq.category category
                WHERE faq.path = :slug
                  AND category.enabled = TRUE
                  AND category.internal = TRUE
                  AND faq.enabled = TRUE
                  AND faq.internal = TRUE
                  AND (((faq.enabledExpiredDate = FALSE OR faq.enabledExpiredDate IS NULL)
                      AND faq.enabledDate <= :dateSelect)
                  OR (faq.enabledExpiredDate = TRUE AND faq.expiredDate >= :dateSelect))
                ORDER BY faq.createdDate DESC
            ')
            ->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('slug', $slug)
            ->getOneOrNullResult();
    }

    /**
     * @return array
     */
    public function getAllFaqWhereEnabledAndInternal()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT faq
                FROM UserBundle:Faq faq
                LEFT JOIN faq.category category
                WHERE category.enabled = TRUE
                  AND category.internal = TRUE
                  AND faq.enabled = TRUE
                  AND faq.internal = TRUE
                  AND (((faq.enabledExpiredDate = FALSE OR faq.enabledExpiredDate IS NULL)
                      AND faq.enabledDate <= :dateSelect)
                  OR (faq.enabledExpiredDate = TRUE AND faq.expiredDate >= :dateSelect))
                ORDER BY faq.createdDate DESC
            ')
            ->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getResult();
    }
}
