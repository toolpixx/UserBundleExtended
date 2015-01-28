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
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser implements AdvancedUserInterface
{
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
    private $usedRoles = array('ROLE_CUSTOMER');

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
}