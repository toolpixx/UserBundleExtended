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

        // Create query
        return $this->getEntityManager()
            ->createQuery(
                '
                SELECT
                  categorys
                FROM
                  UserBundle:NewsCategorys categorys
                WHERE
                    categorys.name LIKE :query
                ORDER BY
                  categorys.id
                ASC
            '
            )
            ->setParameter('query', '%'.$query.'%');
    }
}
