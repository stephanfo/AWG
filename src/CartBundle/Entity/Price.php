<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Price
 *
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\PriceRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Price
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
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 1)
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     * @Assert\NotNull
     * @Assert\Type(type="float")
     * @Assert\Range(min = 0)
     */
    private $price;

    /**
     * @ORM\ManytoOne(targetEntity="CartBundle\Entity\Format", inversedBy="prices")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull(message= "Please select a format.")
     */
    private $format;

    /**
     * @ORM\ManytoOne(targetEntity="CartBundle\Entity\Discount", inversedBy="prices")
     */
    private $discount;


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
     * @return Price
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
     * @return Price
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
     * @return Price
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return Price
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
     * Set price
     *
     * @param float $price
     *
     * @return Price
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set format
     *
     * @param \CartBundle\Entity\Format $format
     *
     * @return Price
     */
    public function setFormat(\CartBundle\Entity\Format $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get format
     *
     * @return \CartBundle\Entity\Format
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set discount
     *
     * @param \CartBundle\Entity\Discount $discount
     *
     * @return Price
     */
    public function setDiscount(\CartBundle\Entity\Discount $discount = null)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return \CartBundle\Entity\Discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }
}
