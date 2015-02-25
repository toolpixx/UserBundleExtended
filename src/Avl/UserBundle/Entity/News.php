<?php

namespace Avl\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\NewsRepository")
 * @ORM\Table(name="news")
 */
class News
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
     * @ORM\Column(
     *  type="boolean",
     *  nullable=true
     * )
     */
    protected $enabled;

    /**
     * @ORM\Column(
     *  type="boolean",
     *  nullable=true
     * )
     */
    protected $internal;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetimetz")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enabledDate", type="datetimetz")
     */
    private $enabledDate;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="News", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="cascade")
     * @Assert\Valid
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct(\Avl\UserBundle\Entity\User $user = null)
    {
        $this->createdDate = new \DateTime();
        $this->enabledDate = new \DateTime();
        $this->user = $user ?: new \Avl\UserBundle\Entity\User();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get enabled
     *
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     *
     * @param $id
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get internal
     *
     * @return mixed
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * Set internal
     *
     * @param $id
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return News
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return News
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate; //->format('d.m.Y H:i:s');
    }

    /**
     * Get createdDateFormatted
     *
     * @return \DateTime
     */
    public function getCreatedDateFormatted()
    {
        return $this->createdDate->format('d.m.Y H:i:s');
    }

    /**
     * Set enabledDate
     *
     * @param \DateTime $enabledDate
     * @return News
     */
    public function setEnabledDate($enabledDate)
    {
        $this->enabledDate = $enabledDate;

        return $this;
    }

    /**
     * Get enabledDate
     *
     * @return \DateTime
     */
    public function getEnabledDate()
    {
        return $this->enabledDate; //->format('d.m.Y H:i:s');
    }

    /**
     * Get enabledDateFormatted
     *
     * @return \DateTime
     */
    public function getEnabledDateFormatted()
    {
        return $this->enabledDate->format('d.m.Y H:i:s');
    }

    /**
     * Set userid
     *
     * @param integer $userid
     * @return News
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userid
     *
     * @return integer 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add userid
     *
     * @param \Avl\UserBundle\Entity\User $userid
     * @return News
     */
    public function addUserid(\Avl\UserBundle\Entity\User $userid)
    {
        $this->userid[] = $userid;

        return $this;
    }

    /**
     * Remove userid
     *
     * @param \Avl\UserBundle\Entity\User $userid
     */
    public function removeUserid(\Avl\UserBundle\Entity\User $userid)
    {
        $this->userid->removeElement($userid);
    }
}
