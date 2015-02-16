<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 14.02.15
 * Time: 23:26
 */
namespace Avl\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllSubUserByParentId($userId, $parentId)
    {
        $query = $this->getEntityManager()
            ->createQuery('
              SELECT
                user
              FROM
                UserBundle:User user
              WHERE
                  user.parentId = :parentId
                AND
                  user.id != :parentId
                AND
                  user.id != :userId
              ORDER BY
                user.id
              ASC
            '
        )
        ->setParameter('userId', $userId)
        ->setParameter('parentId', $parentId);

        return $query->getResult();
    }
}