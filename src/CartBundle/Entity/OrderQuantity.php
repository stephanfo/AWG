<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * OrderQuantity
 *
 * @ORM\Table(name="order_quantity")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\OrderQuantityRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class OrderQuantity
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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

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
     * @ORM\ManyToOne(targetEntity="CartBundle\Entity\Format", inversedBy="orderQuantities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $format;

    /**
     * @ORM\ManyToOne(targetEntity="CartBundle\Entity\Detail", inversedBy="quantities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $detail;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderQuantity
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return OrderQuantity
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
     * @return OrderQuantity
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
     * @return OrderQuantity
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
     * Set format
     *
     * @param \CartBundle\Entity\Format $format
     *
     * @return OrderQuantity
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
     * Set detail
     *
     * @param \CartBundle\Entity\Detail $detail
     *
     * @return OrderQuantity
     */
    public function setDetail(\CartBundle\Entity\Detail $detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return \CartBundle\Entity\Detail
     */
    public function getDetail()
    {
        return $this->detail;
    }
}
