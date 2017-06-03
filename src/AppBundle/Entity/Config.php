<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConfigRepository")
 */
class Config
{

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $applicationTheme;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(type="string")
     */
    private $applicationIntroMessage;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $applicationSellFiles;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $applicationSellFilesAllowDownload;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $applicationSellFilesForceDownload;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\Type(type="string")
     */
    private $applicationSellFilesEmailSender;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $applicationSellFilesEmailSenderInCc;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $applicationSellFilesEmailSubject;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(type="string")
     */
    private $applicationSellFilesEmailBody;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $galleryQuickLink;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $gallerySingleGallery;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $galleryAnonymousAccess;

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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Config
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return Config
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set applicationTheme
     *
     * @param string $applicationTheme
     *
     * @return Config
     */
    public function setApplicationTheme($applicationTheme)
    {
        $this->applicationTheme = $applicationTheme;

        return $this;
    }

    /**
     * Get applicationTheme
     *
     * @return string
     */
    public function getApplicationTheme()
    {
        return $this->applicationTheme;
    }

    /**
     * Set applicationIntroMessage
     *
     * @param string $applicationIntroMessage
     *
     * @return Config
     */
    public function setApplicationIntroMessage($applicationIntroMessage)
    {
        $this->applicationIntroMessage = $applicationIntroMessage;

        return $this;
    }

    /**
     * Get applicationIntroMessage
     *
     * @return string
     */
    public function getApplicationIntroMessage()
    {
        return $this->applicationIntroMessage;
    }

    /**
     * Set galleryQuickLink
     *
     * @param boolean $galleryQuickLink
     *
     * @return Config
     */
    public function setGalleryQuickLink($galleryQuickLink)
    {
        $this->galleryQuickLink = $galleryQuickLink;

        return $this;
    }

    /**
     * Get galleryQuickLink
     *
     * @return boolean
     */
    public function getGalleryQuickLink()
    {
        return $this->galleryQuickLink;
    }

    /**
     * Set applicationSellFiles
     *
     * @param boolean $applicationSellFiles
     *
     * @return Config
     */
    public function setApplicationSellFiles($applicationSellFiles)
    {
        $this->applicationSellFiles = $applicationSellFiles;

        return $this;
    }

    /**
     * Get applicationSellFiles
     *
     * @return boolean
     */
    public function getApplicationSellFiles()
    {
        return $this->applicationSellFiles;
    }

    /**
     * Set applicationSellFilesEmailBody
     *
     * @param string $applicationSellFilesEmailBody
     *
     * @return Config
     */
    public function setApplicationSellFilesEmailBody($applicationSellFilesEmailBody)
    {
        $this->applicationSellFilesEmailBody = $applicationSellFilesEmailBody;

        return $this;
    }

    /**
     * Get applicationSellFilesEmailBody
     *
     * @return string
     */
    public function getApplicationSellFilesEmailBody()
    {
        return $this->applicationSellFilesEmailBody;
    }

    /**
     * Set applicationSellFilesEmailSubject
     *
     * @param string $applicationSellFilesEmailSubject
     *
     * @return Config
     */
    public function setApplicationSellFilesEmailSubject($applicationSellFilesEmailSubject)
    {
        $this->applicationSellFilesEmailSubject = $applicationSellFilesEmailSubject;

        return $this;
    }

    /**
     * Get applicationSellFilesEmailSubject
     *
     * @return string
     */
    public function getApplicationSellFilesEmailSubject()
    {
        return $this->applicationSellFilesEmailSubject;
    }

    /**
     * Set applicationSellFilesEmailSender
     *
     * @param string $applicationSellFilesEmailSender
     *
     * @return Config
     */
    public function setApplicationSellFilesEmailSender($applicationSellFilesEmailSender)
    {
        $this->applicationSellFilesEmailSender = $applicationSellFilesEmailSender;

        return $this;
    }

    /**
     * Get applicationSellFilesEmailSender
     *
     * @return string
     */
    public function getApplicationSellFilesEmailSender()
    {
        return $this->applicationSellFilesEmailSender;
    }

    /**
     * Set applicationSellFilesForceDownload
     *
     * @param boolean $applicationSellFilesForceDownload
     *
     * @return Config
     */
    public function setApplicationSellFilesForceDownload($applicationSellFilesForceDownload)
    {
        $this->applicationSellFilesForceDownload = $applicationSellFilesForceDownload;

        return $this;
    }

    /**
     * Get applicationSellFilesForceDownload
     *
     * @return boolean
     */
    public function getApplicationSellFilesForceDownload()
    {
        return $this->applicationSellFilesForceDownload;
    }

    /**
     * Set applicationSellFilesAllowDownload
     *
     * @param boolean $applicationSellFilesAllowDownload
     *
     * @return Config
     */
    public function setApplicationSellFilesAllowDownload($applicationSellFilesAllowDownload)
    {
        $this->applicationSellFilesAllowDownload = $applicationSellFilesAllowDownload;

        return $this;
    }

    /**
     * Get applicationSellFilesAllowDownload
     *
     * @return boolean
     */
    public function getApplicationSellFilesAllowDownload()
    {
        return $this->applicationSellFilesAllowDownload;
    }

    /**
     * Set applicationSellFilesEmailSenderInCc
     *
     * @param boolean $applicationSellFilesEmailSenderInCc
     *
     * @return Config
     */
    public function setApplicationSellFilesEmailSenderInCc($applicationSellFilesEmailSenderInCc)
    {
        $this->applicationSellFilesEmailSenderInCc = $applicationSellFilesEmailSenderInCc;

        return $this;
    }

    /**
     * Get applicationSellFilesEmailSenderInCc
     *
     * @return boolean
     */
    public function getApplicationSellFilesEmailSenderInCc()
    {
        return $this->applicationSellFilesEmailSenderInCc;
    }

    /**
     * Set gallerySingleGallery
     *
     * @param boolean $gallerySingleGallery
     *
     * @return Config
     */
    public function setGallerySingleGallery($gallerySingleGallery)
    {
        $this->gallerySingleGallery = $gallerySingleGallery;

        return $this;
    }

    /**
     * Get gallerySingleGallery
     *
     * @return boolean
     */
    public function getGallerySingleGallery()
    {
        return $this->gallerySingleGallery;
    }

    /**
     * Set galleryAnonymousAccess
     *
     * @param boolean $galleryAnonymousAccess
     *
     * @return Config
     */
    public function setGalleryAnonymousAccess($galleryAnonymousAccess)
    {
        $this->galleryAnonymousAccess = $galleryAnonymousAccess;

        return $this;
    }

    /**
     * Get galleryAnonymousAccess
     *
     * @return boolean
     */
    public function getGalleryAnonymousAccess()
    {
        return $this->galleryAnonymousAccess;
    }
}
