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
    public function getAllSubUserByParentId($userId, $parentId, $formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';
        $parentId = (null !== $parentId) ? $parentId : $userId;

        return $this->getEntityManager()
            ->createQuery(
                'SELECT
                  user
                FROM
                  UserBundle:User user
                WHERE
                  user.parentId = :parentId
                AND user.id != :parentId
                AND user.id != :userId
                AND (
                      user.email LIKE :query
                    OR
                      user.username LIKE :query
                )
                ORDER BY user.id ASC')
            ->setParameter('userId', $userId)
            ->setParameter('parentId', $parentId)
            ->setParameter('query', '%'.$query.'%');
    }

    /**
     * @param $userId
     * @param $formData
     * @return \Doctrine\ORM\AbstractQuery|string
     */
    public function getAllSubUser($userId, $formData)
    {
        // Setup
        $query = (null !== $formData['query']) ? $formData['query'] : '';

        return $this->getEntityManager()
            ->createQuery(
                'SELECT
                  user.enabled,
                  user.profilePicturePath,
                  user.username,
                  user.email,
                  user.roles,
                  user.id,
                  user.parentId,
                  COALESCE(user.parentId, user.id) as orderTree
                FROM
                  UserBundle:User user
                WHERE (
                    user.email LIKE :query
                  OR
                    user.username LIKE :query
                )
                AND user.id != :userId
                ORDER BY orderTree ASC')
            ->setParameter('userId', $userId)
            ->setParameter('query', '%'.$query.'%');
    }
}
