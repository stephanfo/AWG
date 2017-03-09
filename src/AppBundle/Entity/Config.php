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
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
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
    private $galleryQuickLink;

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
}
