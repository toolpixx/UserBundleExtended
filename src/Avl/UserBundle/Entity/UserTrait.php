<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 10.01.15
 * Time: 21:52
 */
namespace Avl\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * Class UserTrait
 * @package Avl\UserBundle\Entity
 */
trait UserTrait
{
    /**
     * @Assert\Image(
     *  maxWidth = 1024,
     *  maxWidthMessage = "The image width is too big ({{ width }}px). Allowed maximum width is {{ max_width }}px.",
     *  maxHeight = 768,
     *  maxHeightMessage = "The image width is too big ({{ height }}px). Allowed maximum width is {{ max_height }}px.",
     *  mimeTypesMessage = "Please upload a valid image."
     * )
     *
     * http://symfony.com/doc/current/reference/constraints/Image.html
     *
     * @Assert\File(
     *  maxSize = "1M",
     *  mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *  maxSizeMessage = "Die ausgewählte Datei ({{ size }} {{ suffix }}) ist zu gross. Maximal {{ limit }} {{ suffix }} erlaubt.",
     *  mimeTypesMessage = "Ihr Dokument ({{ type }}) hat das falsche Format. Bitte benutzen Sie nur {{ types }}.",
     *  disallowEmptyMessage = "Sie haben eine leere Datei hochgeladen.",
     *  uploadErrorMessage = "Es ist ein unerwarterter Fehler beim Hochladen Ihres Dokumentes aufgetreten."
     * )
     *
     * ValidateRules: http://symfony.com/doc/current/reference/constraints/File.html#mimetypes
     */
    protected $profilePictureFile;

    /**
     * for temporary storage
     *
     * @var string
     */
    private $oldProfilePicturePath;

    /**
     * @ORM\Column(
     *  type="string",
     *  length=255,
     *  nullable=true
     * )
     */
    protected $profilePicturePath;

    /**
     * @var
     */
    protected $imageCropY;

    /**
     * @var
     */
    protected $imageCropX;

    /**
     * @var
     */
    protected $imageCropHeight;

    /**
     * @var
     */
    protected $imageCropWidth;

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropY()
    {
        return $this->imageCropY;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropY
     */
    public function setImageCropY($imageCropY)
    {
        $this->imageCropY = $imageCropY;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropX()
    {
        return $this->imageCropX;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropX
     */
    public function setImageCropX($imageCropX)
    {
        $this->imageCropX = $imageCropX;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropHeight()
    {
        return $this->imageCropHeight;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropHeight
     */
    public function setImageCropHeight($imageCropHeight)
    {
        $this->imageCropHeight = $imageCropHeight;
    }

    /**
     * For cropping profile-picture
     *
     * @return mixed
     */
    public function getImageCropWidth()
    {
        return $this->imageCropWidth;
    }

    /**
     * For cropping profile-picture
     *
     * @param $imageCropWidth
     */
    public function setImageCropWidth($imageCropWidth)
    {
        $this->imageCropWidth = $imageCropWidth;
    }

    /**
     * Sets the file used for profile picture uploads
     *
     * @param  UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(UploadedFile $file = null)
    {
        // set the value of the holder
        $this->profilePictureFile = $file;

        // check if we have an old image path
        if (isset($this->profilePicturePath)) {

            // store the old name to delete after the update
            $this->oldProfilePicturePath = $this->profilePicturePath;
            $this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile()
    {
        return (null !== $this->profilePictureFile) ? $this->profilePictureFile : null;
    }

    /**
     * Set profilePicturePath
     *
     * @param  string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath = '')
    {
        $this->profilePicturePath = $profilePicturePath;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath()
    {
        return null == $this->getProfilePicturePath()
            ? null
            : $this->getUploadRootDir() . '/' . $this->getProfilePicturePath();
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . self::UPLOAD_ROOT_DIR . self::UPLOAD_DIR;
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath()
    {
        return self::UPLOAD_DIR . '/' . $this->getProfilePicturePath();
    }

    /**
     * @return bool
     */
    public function hasProfilePictureUpload()
    {
        return ($this->getProfilePictureFile() instanceof UploadedFile) ? true : false;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture()
    {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded, generate a unique filename
            $filename = $this->generateRandomFilename();
            $this->setProfilePicturePath(
                $filename . '.' . $this->getProfilePictureFile()->guessExtension()
            );
        }
    }

    /**
     * Generates a 32 char long random filename.
     *
     * The while-loop lookup if any other
     * file with generated name exists. If
     * yes he move to next random and so on.
     *
     * @return string
     */
    public function generateRandomFilename()
    {
        // Local variable
        $count = 0;

        // Generate..
        do {
            $generator = new SecureRandom();
            $random = $generator->nextBytes(16);
            $randomString = bin2hex($random);
            $count++;
        } while (file_exists($this->getUploadRootDir() . '/' . $randomString . '.' . $this->getProfilePictureFile()->guessExtension()) && $count < 50);

        return $randomString;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadProfilePicture()
    {
        // check there is a profile pic to upload
        if ($this->getProfilePictureFile() === null) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getProfilePictureFile()->move(
            $this->getUploadRootDir(),
            $this->getProfilePicturePath()
        );

        // check if we have an old image
        if (true
            && isset($this->oldProfilePicturePath)
            && file_exists($this->getUploadRootDir() . '/' . $this->oldProfilePicturePath)
        ) {
            // delete the old image
            if (is_file($this->getUploadRootDir() . '/' . $this->oldProfilePicturePath)) {

                unlink($this->getUploadRootDir() . '/' . $this->oldProfilePicturePath);
                // clear the temp image path
                $this->oldProfilePicturePath = null;
            }
        }
        $this->profilePictureFile = null;
    }

    public function getUploadDir()
    {
        return self::UPLOAD_DIR;
    }

    /**
     * Remove image
     *
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        // Check the profile-picture
        if (true
            && ($file = $this->getProfilePictureAbsolutePath())
            && file_exists($this->getProfilePictureAbsolutePath())
        ) {
            // Delete profile-picture
            if (unlink($file)) {
                // Set profile-picture to empty
                $this->setProfilePicturePath();

                return true;
            }
        }
        return false;
    }
}