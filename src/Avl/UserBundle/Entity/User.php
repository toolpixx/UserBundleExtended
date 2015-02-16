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

use Symfony\Component\Intl\Locale\Locale as Locale;

/**
 * @ORM\Entity(repositoryClass="Avl\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements AdvancedUserInterface
{
    /**
     * German locale
     */
    const DEFAULT_LOCALE_DE = 'de_DE';
    const DEFAULT_LOCALE_DE_NAME = 'German';

    /**
     * English locale
     */
    const DEFAULT_LOCALE_EN = 'en_EN';
    const DEFAULT_LOCALE_EN_NAME = 'English';

    /**
     * Spanish locale
     */
    const DEFAULT_LOCALE_ES = 'es_ES';
    const DEFAULT_LOCALE_ES_NAME = 'Spain';

    /**
     * Francais locale
     */
    const DEFAULT_LOCALE_FR = 'fr_FR';
    const DEFAULT_LOCALE_FR_NAME = 'Francais';

    /**
     * Italy locale
     */
    const DEFAULT_LOCALE_IT = 'it_IT';
    const DEFAULT_LOCALE_IT_NAME = 'Italy';

    /**
     * @var string
     */
    private $uploadRootDir = '/../../../../web';

    /**
     * @var string
     */
    private $uploadDir = '/uploads/user/profilepics';

    /**
     * @var Session
     */
    private $session;

    /**
     * Add more roles if you wan't. But do not forget
     * to define the roles in security.yaml
     *
     * @var array
     */
    private $usedRoles; // array('ROLE_ADMIN', 'ROLE_CUSTOMER');

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
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
     *  @ORM\Column(
     *  type="datetimetz",
     *  nullable=true)
     */
    protected $createdDate;

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
        parent::__construct();
        // your own logic

        /**
         * Here you can setup the roles if you want. This
         * roles will set anyway. I have setup roles in
         *
         * Avl\UserBundle\EventListener\RegistrationListener
         *
         * $this->roles = $this->usedRoles;
         */
    }

    /**
     * Get the parentId from user
     *
     * @return mixed
     */
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * Set the parentId for user
     *
     * @param $id
     */
    public function setParentId($id) {
        $this->parentId = $id;
    }

    /**
     * Create values for the locale dropdown.
     * @return array
     */
    public static function getUsedRoles() {
        return array(
            'ROLE_CUSTOMER_EVENT_MANAGER' => 'ROLE_CUSTOMER_EVENT_MANAGER',
            'ROLE_CUSTOMER_COMMENT_MANAGER' => 'ROLE_CUSTOMER_COMMENT_MANAGER',
            'ROLE_CUSTOMER_SUBUSER_MANAGER' => 'ROLE_CUSTOMER_SUBUSER_MANAGER'
        );
    }

    /**
     * Returns the locale of a user
     * @return mixed
     */
    public function getLocale() {
        return (null !== $this->locale) ? $this->locale : Locale::getDefault();
    }

    /**
     * Set the locale of a user
     * @param string $locale
     */
    public function setLocale($locale) {
        $this->locale = (null !== $locale) ? $locale : Locale::getDefault();
    }

    /**
     * Create values for the locale dropdown.
     * @return array
     */
    public static function getLocaleNames() {
        return array(
            self::DEFAULT_LOCALE_EN => self::DEFAULT_LOCALE_EN_NAME,
            self::DEFAULT_LOCALE_DE => self::DEFAULT_LOCALE_DE_NAME,
            self::DEFAULT_LOCALE_ES => self::DEFAULT_LOCALE_ES_NAME,
            self::DEFAULT_LOCALE_FR => self::DEFAULT_LOCALE_FR_NAME,
            self::DEFAULT_LOCALE_IT => self::DEFAULT_LOCALE_IT_NAME,
        );
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
}