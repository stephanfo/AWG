<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Price
 *
 * @ORM\Table(name="discount")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\DiscountRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Discount
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $title;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type(type="string")
     */
    private $detail;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $comment;

    /**
     * @var \DateTime $startTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $startTime;

    /**
     * @var \DateTime $stopTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     */
    private $stopTime;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Type(type="integer")
     */
    private $discount;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $discountStart;

    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $discountStop;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\Price", mappedBy="discount", cascade={"remove"})
     */
    private $prices;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    private $active;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Discount
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
     * @return Discount
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
     * @return Discount
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
     * Set title
     *
     * @param string $title
     *
     * @return Discount
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
     * Set detail
     *
     * @param string $detail
     *
     * @return Discount
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Discount
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Discount
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set stopTime
     *
     * @param \DateTime $stopTime
     *
     * @return Discount
     */
    public function setStopTime($stopTime)
    {
        $this->stopTime = $stopTime;

        return $this;
    }

    /**
     * Get stopTime
     *
     * @return \DateTime
     */
    public function getStopTime()
    {
        return $this->stopTime;
    }

    /**
     * Set discount
     *
     * @param integer $discount
     *
     * @return Discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * Get discount
     *
     * @return integer
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Add price
     *
     * @param \CartBundle\Entity\Price $price
     *
     * @return Discount
     */
    public function addPrice(\CartBundle\Entity\Price $price)
    {
        $this->prices[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param \CartBundle\Entity\Price $price
     */
    public function removePrice(\CartBundle\Entity\Price $price)
    {
        $this->prices->removeElement($price);
    }

    /**
     * Get prices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrices()
    {
        return $this->prices;
    }

    /**
     * Set discountStart
     *
     * @param float $discountStart
     *
     * @return Discount
     */
    public function setDiscountStart($discountStart)
    {
        $this->discountStart = $discountStart;

        return $this;
    }

    /**
     * Get discountStart
     *
     * @return float
     */
    public function getDiscountStart()
    {
        return $this->discountStart;
    }

    /**
     * Set discountStop
     *
     * @param float $discountStop
     *
     * @return Discount
     */
    public function setDiscountStop($discountStop)
    {
        $this->discountStop = $discountStop;

        return $this;
    }

    /**
     * Get discountStop
     *
     * @return float
     */
    public function getDiscountStop()
    {
        return $this->discountStop;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Discount
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }
}
