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
 * Class UserRepository
 * @package Avl\UserBundle\Entity
 */
class UserRepository extends EntityRepository
{

    /**
     * @param $userId
     * @param $parentId
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function findAllSubUserByParentId($userId, $parentId, $formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';
        $parentId = (null !== $parentId) ? $parentId : $userId;

        // Create query
        $query = $this->getEntityManager()
            ->createQuery(
                '
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
                AND (
                      user.email LIKE :query
                    OR
                      user.username LIKE :query
                )
                ORDER BY
                  user.id
                ASC
            '
            )
            ->setParameter('userId', $userId)
            ->setParameter('parentId', $parentId)
            ->setParameter('query', '%'.$query.'%');

        return $query;
    }

    /**
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery|string
     */
    public function findAllSubUser($formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        // Query
        $query = $this->getEntityManager()
            ->createQuery(
                '
                SELECT
                  user
                FROM
                  UserBundle:User user
                WHERE
                  user.email LIKE :query
                OR
                  user.username LIKE :query
                ORDER BY
                  user.id
                ASC
            '
            )
            ->setParameter('query', '%'.$query.'%');

        return $query;
    }
}