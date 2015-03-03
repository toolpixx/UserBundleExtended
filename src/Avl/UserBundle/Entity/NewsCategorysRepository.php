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
 * Class NewsCategorysRepository
 * @package Avl\UserBundle\Entity
 */
class NewsCategorysRepository extends EntityRepository
{
    /**
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function getAllNewsCategorysByQuery($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        return $this->getEntityManager()
            ->createQuery('
                SELECT categorys
                FROM UserBundle:NewsCategorys categorys
                WHERE categorys.name LIKE :query
                ORDER BY categorys.id ASC
            ')
            ->setParameter('query', '%'.$query.'%');
    }

    public function getAllNewsCategoryWhereNewsEnabledAndInternal()
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT categorys, news
                FROM UserBundle:NewsCategorys categorys
                LEFT JOIN categorys.news news
                WHERE categorys.enabled = TRUE
                  AND categorys.internal = TRUE
                  AND (news.enabled = TRUE
                  AND news.internal = TRUE
                  AND ((news.enabledExpiredDate = FALSE OR news.enabledExpiredDate IS NULL)
                      AND news.enabledDate <= :dateSelect)
                  OR (news.enabledExpiredDate = TRUE AND news.expiredDate >= :dateSelect))
                ORDER BY categorys.name ASC
            ')
            ->setParameter('dateSelect', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->getResult();
    }
}
