<?php

namespace Avl\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\FaqRepository")
 * @ORM\Table(name="faq")
 * @UniqueEntity("path")
 * @UniqueEntity("question")
 * @ORM\HasLifecycleCallbacks
 */
class Faq
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
     * @ORM\Column(name="question", type="string", length=100, unique=true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text", nullable=true)
     */
    private $answer;

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
     * @ORM\Column(name="expiredDate", type="datetimetz", nullable=true)
     */
    private $expiredDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enabledExpiredDate", type="boolean", nullable=true)
     */
    private $enabledExpiredDate;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="faq", cascade={"persist"})
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="FaqCategorys", inversedBy="faq", cascade={"persist"})
     * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * @Assert\Valid
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity="Faq")
     * @ORM\JoinTable(name="faq_related")
     * @Assert\Valid
     */
    private $related;

    /**
     * Constructor
     */
    public function __construct(User $user = null)
    {
        $this->createdDate = new \DateTime();
        $this->enabledDate = new \DateTime();
        $this->expiredDate = new \DateTime();
        $this->related = new ArrayCollection();
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
     * @param $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param string $answer
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    /**
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param $data
     * @return string
     */
    public function setPathReplace($data)
    {
        $path = $data['path'];
        if (empty($path)) {
            $path = $data['question'];
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
        if (null !== $this->expiredDate && '-0001' != date('Y', $this->expiredDate->getTimestamp())) {
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

    /**
     * @param integer $related
     */
    public function setRelated($related)
    {
        $this->related = $related;
    }

    /**
     * @return integer
     */
    public function getRelated()
    {
        return $this->related;
    }
}
