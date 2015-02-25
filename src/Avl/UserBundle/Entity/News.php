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
    public function __construct(User $user = null)
    {
        $this->createdDate = new \DateTime();
        $this->enabledDate = new \DateTime();
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
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getInternal()
    {
        return $this->internal;
    }

    /**
     * @param $internal
     */
    public function setInternal($internal)
    {
        $this->internal = $internal;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
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
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDateFormatted()
    {
        return $this->createdDate->format('d.m.Y H:i:s');
    }

    /**
     * @param \DateTime $enabledDate
     */
    public function setEnabledDate($enabledDate)
    {
        $this->enabledDate = $enabledDate;
    }

    /**
     * @return \DateTime
     */
    public function getEnabledDate()
    {
        return $this->enabledDate;
    }

    /**
     * @return string
     */
    public function getEnabledDateFormatted()
    {
        return $this->enabledDate->format('d.m.Y H:i:s');
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
}
