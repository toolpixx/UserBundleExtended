<?php

namespace Avl\UserBundle\Entity;

use Avl\UserBundle\Entity\NewsGroups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\NewsRepository")
 * @ORM\Table(name="news")
 * @UniqueEntity("path")
 * @UniqueEntity("title")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(name="title", type="string", length=100, unique=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="preface", type="text")
     */
    private $preface;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true, unique=true)
     */
    private $path;

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
     * @var \DateTime
     *
     * @ORM\Column(name="expiredDate", type="datetimetz")
     */
    private $expiredDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enabledExpiredDate", type="boolean", nullable=true)
     */
    private $enabledExpiredDate;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="NewsCategorys", inversedBy="news", cascade={"persist"})
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct(User $user = null)
    {
        $this->createdDate = new \DateTime();
        $this->enabledDate = new \DateTime();
        $this->expiredDate = new \DateTime();
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
     * @param string $preface
     */
    public function setPreface($preface)
    {
        $this->preface = $preface;
    }

    /**
     * @return string
     */
    public function getPreface()
    {
        return $this->preface;
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
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param $data
     * @return string
     */
    public function setPathReplace($data)
    {
        $path = $data['path'];
        if (empty($path)) {
            $path = $data['title'];
        }
        $path = preg_replace('/\s/', '_', $path);
        $path = preg_replace('/[^a-zA-Z0-9_]/sm', '', $path);
        return strtolower($path);
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * @param \DateTime $expiredDate
     */
    public function setExpiredDate($expiredDate)
    {
        $this->expiredDate = $expiredDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredDate()
    {
        if ('-0001' != date('Y', $this->expiredDate->getTimestamp())) {
            return $this->expiredDate;
        }
        return new \DateTime();
    }

    /**
     * @return string
     */
    public function getExpiredDateFormatted()
    {
        return $this->expiredDate->format('d.m.Y H:i:s');
    }

    /**
     * @return mixed
     */
    public function getEnabledExpiredDate()
    {
        return $this->enabledExpiredDate;
    }

    /**
     * @param $enabled
     */
    public function setEnabledExpiredDate($enabledExpiredDate)
    {
        $this->enabledExpiredDate = $enabledExpiredDate;
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
     * @param integer $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }
}
