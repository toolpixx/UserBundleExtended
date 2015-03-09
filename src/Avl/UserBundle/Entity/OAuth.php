<?php

namespace Avl\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\OAuthRepository")
 * @ORM\Table(name="oauth")
 */
class OAuth
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="oauth", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $user;

    /**
     * @var array
     *
     * @ORM\Column(name="accounts", type="array")
     */
    private $accounts;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="array", nullable=true)
     */
    private $info;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedDate", type="datetimetz")
     */
    private $updatedDate;

    /**
     * Constructor
     */
    public function __construct(User $user = null)
    {
        $this->updatedDate = new \DateTime();
        $this->user = $user ?: new User();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getUnserializeInfo()
    {
        return unserialize($this->info);
    }

    /**
     * @param $info
     */
    public function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * @return string
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    public function getUnserializeAccounts()
    {
        return $this->accounts;
    }

    /**
     * @param $accounts
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDateFormatted()
    {
        return $this->updatedDate->format('d.m.Y H:i:s');
    }
}
