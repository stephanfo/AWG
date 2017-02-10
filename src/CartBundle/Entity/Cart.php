<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\CartRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Cart
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
     * @var \DateTime $deleted
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleted;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\CartQuantity", mappedBy="cart", cascade={"remove"})
     */
    private $quantities;

    /**
     * @ORM\ManytoOne(targetEntity="GalleryBundle\Entity\Photo", inversedBy="carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    /**
     * @ORM\ManytoOne(targetEntity="UserBundle\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quantities = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Cart
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
     * @return Cart
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
     * @return Cart
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
     * Add quantity
     *
     * @param \CartBundle\Entity\CartQuantity $quantity
     *
     * @return Cart
     */
    public function addQuantity(\CartBundle\Entity\CartQuantity $quantity)
    {
        $this->quantities[] = $quantity;

        return $this;
    }

    /**
     * Remove quantity
     *
     * @param \CartBundle\Entity\CartQuantity $quantity
     */
    public function removeQuantity(\CartBundle\Entity\CartQuantity $quantity)
    {
        $this->quantities->removeElement($quantity);
    }

    /**
     * Get quantities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuantities()
    {
        return $this->quantities;
    }

    /**
     * Set photo
     *
     * @param \GalleryBundle\Entity\Photo $photo
     *
     * @return Cart
     */
    public function setPhoto(\GalleryBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \GalleryBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Cart
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
