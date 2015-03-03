<?php

namespace Avl\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\NewsCategorysRepository")
 * @ORM\Table(name="news_categorys")
 * @UniqueEntity("path")
 * @UniqueEntity("name")
 * @ORM\HasLifecycleCallbacks
 */
class NewsCategorys
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
     * @ORM\Column(name="name", type="string", length=100, unique=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true, unique=true)
     */
    protected $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetimetz")
     */
    protected $createdDate;

    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="category", cascade={"persist"})
     * @ORM\JoinColumn(name="news", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $news;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdDate = new \DateTime();
        $this->news = new ArrayCollection();
    }

    public function getNews()
    {
        return $this->news;
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
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $data
     * @return string
     */
    public function setPathReplace($data)
    {
        $path = $data['path'];
        if (empty($path)) {
            $path = $data['name'];
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
}
