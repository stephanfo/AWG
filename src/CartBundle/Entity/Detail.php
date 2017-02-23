<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Detail
 *
 * @ORM\Table(name="orderdetail")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\DetailRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Detail
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
     * @ORM\ManyToOne(targetEntity="CartBundle\Entity\Order", inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="GalleryBundle\Entity\Photo", inversedBy="details")
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\OrderQuantity", mappedBy="detail", cascade={"remove"})
     */
    private $quantities;

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
     * @return Detail
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
     * @return Detail
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
     * @return Detail
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
     * Set order
     *
     * @param \CartBundle\Entity\Order $order
     *
     * @return Detail
     */
    public function setOrder(\CartBundle\Entity\Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \CartBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set photo
     *
     * @param \GalleryBundle\Entity\Photo $photo
     *
     * @return Detail
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
     * Add quantity
     *
     * @param \CartBundle\Entity\OrderQuantity $quantity
     *
     * @return Detail
     */
    public function addQuantity(\CartBundle\Entity\OrderQuantity $quantity)
    {
        $this->quantities[] = $quantity;

        return $this;
    }

    /**
     * Remove quantity
     *
     * @param \CartBundle\Entity\OrderQuantity $quantity
     */
    public function removeQuantity(\CartBundle\Entity\OrderQuantity $quantity)
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
}
