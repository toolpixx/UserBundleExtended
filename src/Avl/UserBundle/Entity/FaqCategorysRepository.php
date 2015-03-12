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
 * Class FaqCategorysRepository
 * @package Avl\UserBundle\Entity
 */
class FaqCategorysRepository extends EntityRepository
{
    /**
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function getAllFaqCategorysByQuery($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        return $this->getEntityManager()
            ->createQuery('
                SELECT categorys
                FROM UserBundle:FaqCategorys categorys
                WHERE categorys.name LIKE :query
                ORDER BY categorys.id ASC
            ')
            ->setParameter('query', '%'.$query.'%');
    }

    public function getAllFaqCategoryWhereFaqEnabledAndInternal()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT categorys, faq
                FROM UserBundle:FaqCategorys categorys
                LEFT JOIN categorys.faq faq
                WHERE categorys.enabled = TRUE
                  AND categorys.internal = TRUE
                  AND (faq.enabled = TRUE
                  AND faq.internal = TRUE
                  AND ((faq.enabledExpiredDate = FALSE OR faq.enabledExpiredDate IS NULL)
                      AND faq.enabledDate <= :dateSelect)
                  OR (faq.enabledExpiredDate = TRUE AND faq.expiredDate >= :dateSelect))
                ORDER BY categorys.name ASC
            ')
            ->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getResult();
    }
}
