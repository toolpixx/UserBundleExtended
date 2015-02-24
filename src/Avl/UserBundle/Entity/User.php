<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements AdvancedUserInterface
{
    /**
     * Default locale
     */
    const DEFAULT_LOCALE = 'de_DE';

    /**
     * @var array
     */
    public static $defaultLocaleNames = array(
        'en_EN' => 'TITLE_EN',
        'de_DE' => 'TITLE_DE',
        'es_ES' => 'TITLE_ES',
        'fr_FR' => 'TITLE_FR',
        'it_IT' => 'TITLE_IT'
    );

    /**
     * @var string
     */
    const UPLOAD_ROOT_DIR = '/../../../../web';

    /**
     * @var string
     */
    const UPLOAD_DIR = '/uploads/user/profilepics';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    protected $parentId;

    /**
     * @ORM\Column(
     *  type="string",
     *  length=8,
     *  nullable=true
     * )
     */
    protected $locale;

    /**
     * @ORM\Column(
     *  type="datetimetz",
     *  nullable=true)
     */
    protected $createdDate;

    /**
     * @Assert\Regex(
     *  pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
     *  message="Password must be seven or more characters long and contain at least one digit, one upper- and one lowercase character."
     * )
     */
    protected $plainPassword;

    /**
     * We will use trait-"class" to use
     * things for upload later on other
     * places, too...
     */
    use UserTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        // BaseUser from FOSUB
        parent::__construct();

        // Set the standard-locale
        $this->setLocale();
    }

    /**
     * Get the parentId from user
     *
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set the parentId for user
     *
     * @param $id
     */
    public function setParentId($id)
    {
        $this->parentId = $id;
    }

    /**
     * Create values for the locale drop-down.
     * @return array
     */
    public static function getUsedRoles()
    {
        return array(
            'ROLE_CUSTOMER_EVENT_MANAGER' => 'ROLE_CUSTOMER_EVENT_MANAGER',
            'ROLE_CUSTOMER_COMMENT_MANAGER' => 'ROLE_CUSTOMER_COMMENT_MANAGER',
            'ROLE_CUSTOMER_SUBUSER_MANAGER' => 'ROLE_CUSTOMER_SUBUSER_MANAGER'
        );
    }

    /**
     * Set the standard-roles for user
     */
    public function setUsedRoles()
    {
        // Set the standard-roles
        $this->setRoles(
            array_keys(
                $this->getUsedRoles()
            )
        );
    }

    /**
     * Create values for the locale drop-down.
     * @return array
     */
    public static function getAdminRoles()
    {
        return array(
            'ROLE_ADMIN' => 'ROLE_ADMIN'
        );
    }

    /**
     * Set the admin-roles for user
     */
    public function setAdminRoles()
    {
        // Set the admin-roles if selected
        $this->setRoles(
            array_keys(
                $this->getAdminRoles()
            )
        );
    }

    /**
     * Returns the locale of a user
     * @return mixed
     */
    public function getLocale()
    {
        return (null !== $this->locale) ? $this->locale : self::DEFAULT_LOCALE;
    }

    /**
     * Set the locale of a user
     * @param string $locale
     */
    public function setLocale($locale = null)
    {
        $this->locale = (null !== $locale) ? $locale : self::DEFAULT_LOCALE;
    }

    /**
     * Create values for the locale drop-down.
     * @return array
     */
    public static function getLocaleNames()
    {
        return self::$defaultLocaleNames;
    }

    /**
     * Set createdDate
     *
     * @param $createdDate
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
     * Get createdDateFormatted
     *
     * @return \DateTime
     */
    public function getCreatedDateFormatted()
    {
        return $this->createdDate->format('d.m.Y H:i:s');
    }
}
