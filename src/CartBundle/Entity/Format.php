<?php

namespace CartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Format
 *
 * @ORM\Table(name="format")
 * @ORM\Entity(repositoryClass="CartBundle\Repository\FormatRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deleted", timeAware=false)
 */
class Format
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
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     */
    private $size;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     */
    private $print;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\Type(type="string")
     */
    private $printSquare;

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
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\CartQuantity", mappedBy="format", cascade={"remove"})
     */
    private $quantities;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\OrderQuantity", mappedBy="format", cascade={"remove"})
     */
    private $orderQuantities;

    /**
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\Price", mappedBy="format", cascade={"remove"})
     */
    private $prices;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->quantities = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orderQuantities = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set size
     *
     * @param string $size
     *
     * @return Format
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Format
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
     * @return Format
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
     * @return Format
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
     * @return Format
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
     * Add orderQuantity
     *
     * @param \CartBundle\Entity\OrderQuantity $orderQuantity
     *
     * @return Format
     */
    public function addOrderQuantity(\CartBundle\Entity\OrderQuantity $orderQuantity)
    {
        $this->orderQuantities[] = $orderQuantity;

        return $this;
    }

    /**
     * Remove orderQuantity
     *
     * @param \CartBundle\Entity\OrderQuantity $orderQuantity
     */
    public function removeOrderQuantity(\CartBundle\Entity\OrderQuantity $orderQuantity)
    {
        $this->orderQuantities->removeElement($orderQuantity);
    }

    /**
     * Get orderQuantities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderQuantities()
    {
        return $this->orderQuantities;
    }

    /**
     * Add price
     *
     * @param \CartBundle\Entity\Price $price
     *
     * @return Format
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
     * Set print
     *
     * @param string $print
     *
     * @return Format
     */
    public function setPrint($print)
    {
        $this->print = $print;

        return $this;
    }

    /**
     * Get print
     *
     * @return string
     */
    public function getPrint()
    {
        return $this->print;
    }

    /**
     * Set printSquare
     *
     * @param string $printSquare
     *
     * @return Format
     */
    public function setPrintSquare($printSquare)
    {
        $this->printSquare = $printSquare;

        return $this;
    }

    /**
     * Get printSquare
     *
     * @return string
     */
    public function getPrintSquare()
    {
        return $this->printSquare;
    }
}
