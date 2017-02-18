<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Order
 *
 * @ORM\Table(name="orderheader")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\OrderRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Order
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
     * @ORM\ManytoOne(targetEntity="UserBundle\Entity\User", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $grossTotal;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $discountTitle;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $discountValue;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $discountSaving;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\Detail", mappedBy="order", cascade={"remove", "persist"})
     */
    private $details;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $payed;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $printed;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $canceled;

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
        $this->details = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set grossTotal
     *
     * @param float $grossTotal
     *
     * @return Order
     */
    public function setGrossTotal($grossTotal)
    {
        $this->grossTotal = $grossTotal;

        return $this;
    }

    /**
     * Get grossTotal
     *
     * @return float
     */
    public function getGrossTotal()
    {
        return $this->grossTotal;
    }

    /**
     * Set discountTitle
     *
     * @param string $discountTitle
     *
     * @return Order
     */
    public function setDiscountTitle($discountTitle)
    {
        $this->discountTitle = $discountTitle;

        return $this;
    }

    /**
     * Get discountTitle
     *
     * @return string
     */
    public function getDiscountTitle()
    {
        return $this->discountTitle;
    }

    /**
     * Set discountValue
     *
     * @param integer $discountValue
     *
     * @return Order
     */
    public function setDiscountValue($discountValue)
    {
        $this->discountValue = $discountValue;

        return $this;
    }

    /**
     * Get discountValue
     *
     * @return integer
     */
    public function getDiscountValue()
    {
        return $this->discountValue;
    }

    /**
     * Set discountSaving
     *
     * @param float $discountSaving
     *
     * @return Order
     */
    public function setDiscountSaving($discountSaving)
    {
        $this->discountSaving = $discountSaving;

        return $this;
    }

    /**
     * Get discountSaving
     *
     * @return float
     */
    public function getDiscountSaving()
    {
        return $this->discountSaving;
    }

    /**
     * Set total
     *
     * @param float $total
     *
     * @return Order
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return float
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Order
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set payed
     *
     * @param boolean $payed
     *
     * @return Order
     */
    public function setPayed($payed)
    {
        $this->payed = $payed;

        return $this;
    }

    /**
     * Get payed
     *
     * @return boolean
     */
    public function getPayed()
    {
        return $this->payed;
    }

    /**
     * Set printed
     *
     * @param boolean $printed
     *
     * @return Order
     */
    public function setPrinted($printed)
    {
        $this->printed = $printed;

        return $this;
    }

    /**
     * Get printed
     *
     * @return boolean
     */
    public function getPrinted()
    {
        return $this->printed;
    }

    /**
     * Set canceled
     *
     * @param boolean $canceled
     *
     * @return Order
     */
    public function setCanceled($canceled)
    {
        $this->canceled = $canceled;

        return $this;
    }

    /**
     * Get canceled
     *
     * @return boolean
     */
    public function getCanceled()
    {
        return $this->canceled;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Order
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
     * @return Order
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
     * @return Order
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Order
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

    /**
     * Add detail
     *
     * @param \CartBundle\Entity\Detail $detail
     *
     * @return Order
     */
    public function addDetail(\CartBundle\Entity\Detail $detail)
    {
        $this->details[] = $detail;

        return $this;
    }

    /**
     * Remove detail
     *
     * @param \CartBundle\Entity\Detail $detail
     */
    public function removeDetail(\CartBundle\Entity\Detail $detail)
    {
        $this->details->removeElement($detail);
    }

    /**
     * Get details
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetails()
    {
        return $this->details;
    }
}
