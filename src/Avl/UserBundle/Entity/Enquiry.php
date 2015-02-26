<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 22:35
 */
namespace Avl\UserBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile as UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Enquiry
 * @package Avl\UserBundle\Entity
 */
class Enquiry
{
    /**
     * The name of customer who send the email
     *
     * @var string
     *
     * @Assert\NotBlank(
     *  message="Bitte geben Sie Ihren Namen an."
     * )
     *
     * @Assert\Length(
     *  min="3",
     *  minMessage="Bitte geben Sie mindestens 3 Zeichen für Ihren Namen ein."
     * )
     *
     * @Assert\Length(
     *  max="50",
     *  maxMessage="Bitte geben Sie maximal 50 Zeichen für Ihren Namen ein."
     * )
     */
    protected $name;

    /**
     * The email of customer who send the email
     *
     * @var string
     *
     * @Assert\NotBlank(
     *  message="Bitte geben Sie Ihre E-Mail-Adresse ein."
     * )
     */
    protected $email;

    /**
     * The subject for the email
     *
     * @var string
     *
     * @Assert\NotBlank(
     *  message="Bitte geben Sie Ihr Anliegen ein."
     * )
     *
     * @Assert\Length(
     *  min="3",
     *  minMessage="Bitte geben Sie mindestens 3 Zeichen für Ihr Anliegen ein."
     * )
     *
     * @Assert\Length(
     *  max="50",
     *  maxMessage="Bitte geben Sie maximal 80 Zeichen für Ihr Anliegen ein."
     * )
     */
    protected $subject;

    /**
     * The subject for the email
     *
     * @var string
     *
     * @Assert\NotBlank(
     *  message="Bitte geben Sie Ihre Anfrage ein."
     * )
     *
     * @Assert\Length(
     *  min="3",
     *  minMessage="Bitte geben Sie mindestens 20 Zeichen für Ihre Anfrage ein."
     * )
     *
     * @Assert\Length(
     *  max="50",
     *  maxMessage="Bitte geben Sie maximal 500 Zeichen für Ihre Anfrage ein."
     * )
     */
    protected $body;

    /**
     * The attachment for the email
     *
     * @var UploadedFile
     *
     * @Assert\File(
     *  maxSize = "2M",
     *  mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *  maxSizeMessage = "Die ausgewählte Datei ({{ size }} {{ suffix }}) ist zu gross. Maximal {{ limit }} {{ suffix }} erlaubt.",
     *  mimeTypesMessage = "Ihr Dokument ({{ type }}) hat das falsche Format. Bitte benutzen Sie nur {{ types }}.",
     *  disallowEmptyMessage = "Sie haben eine leere Datei hochgeladen.",
     *  uploadErrorMessage = "Es ist ein unerwarterter Fehler beim Hochladen Ihres Dokumentes aufgetreten."
     * )
     *
     * ValidateRules: http://symfony.com/doc/current/reference/constraints/File.html#mimetypes
     */
    protected $attachment;

    /**
     * @var User
     */
    private $user;

    public function __construct(User $user = null)
    {
        $this->user = $user ?: new User();
        if (null !== $this->user) {
            $this->setName($user->getUsername());
            $this->setEmail($user->getEmail());
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return UploadedFile
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param $attachment
     */
    public function setAttachment(UploadedFile $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @return bool
     */
    public function hasAttachment()
    {
        return ($this->getAttachment() instanceof UploadedFile) ? true : false;
    }
}
