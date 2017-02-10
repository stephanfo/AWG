<?php

namespace GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Photo
 *
 * @ORM\Table(name="photo")
 * @ORM\Entity(repositoryClass="GalleryBundle\Repository\PhotoRepository")
 *
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Photo
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $title;

    /**
     * @Vich\UploadableField(mapping="gallery_photos", fileNameProperty="imageName")
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $imageName;

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
     * @var \DateTime $deleted
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\ManyToOne(targetEntity="GalleryBundle\Entity\Gallery", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull(message= "Please select a gallery.")
     */
    private $gallery;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\Cart", mappedBy="photo", cascade={"remove"})
     */
    private $carts;

    /**
     * @ORM\ManytoMany(targetEntity="UserBundle\Entity\User", inversedBy="likePhotos")
     */
    private $likeUsers;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $likeCount;

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
     * Set title
     *
     * @param string $title
     *
     * @return Photo
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Photo
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updated = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Photo
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Photo
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
     * @return Photo
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
     * Set deleted
     *
     * @param \DateTime $deleted
     *
     * @return Photo
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return \DateTime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set gallery
     *
     * @param \GalleryBundle\Entity\Gallery $gallery
     *
     * @return Photo
     */
    public function setGallery(\GalleryBundle\Entity\Gallery $gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return \GalleryBundle\Entity\Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->carts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cart
     *
     * @param \CartBundle\Entity\Cart $cart
     *
     * @return Photo
     */
    public function addCart(\CartBundle\Entity\Cart $cart)
    {
        $this->carts[] = $cart;

        return $this;
    }

    /**
     * Remove cart
     *
     * @param \CartBundle\Entity\Cart $cart
     */
    public function removeCart(\CartBundle\Entity\Cart $cart)
    {
        $this->carts->removeElement($cart);
    }

    /**
     * Get carts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * Add likeUser
     *
     * @param \UserBundle\Entity\User $likeUser
     *
     * @return Photo
     */
    public function addLikeUser(\UserBundle\Entity\User $likeUser)
    {
        $this->likeUsers[] = $likeUser;

        return $this;
    }

    /**
     * Remove likeUser
     *
     * @param \UserBundle\Entity\User $likeUser
     */
    public function removeLikeUser(\UserBundle\Entity\User $likeUser)
    {
        $this->likeUsers->removeElement($likeUser);
    }

    /**
     * Get likeUsers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikeUsers()
    {
        return $this->likeUsers;
    }

    /**
     * Set likeCount
     *
     * @param integer $likeCount
     *
     * @return Photo
     */
    public function setLikeCount($likeCount)
    {
        $this->likeCount = $likeCount;

        return $this;
    }

    /**
     * Get likeCount
     *
     * @return integer
     */
    public function getLikeCount()
    {
        return $this->likeCount;
    }

    /**
     * Set increaseLikeCount
     *
     * @return Photo
     */
    public function increaseLikeCount()
    {
        $this->likeCount += 1;

        return $this;
    }

    /**
     * Set decreaseLikeCount
     *
     * @return Photo
     */
    public function decreaseLikeCount()
    {
        $this->likeCount -= 1;

        return $this;
    }

}
