<?php

namespace CartBundle\Classes;

use Doctrine\ORM\EntityManager;

class PriceCalculator
{

    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Array format
     *
     * Return
     * ["details"]
     *   [0]
     *      - format
     *      - quantity
     *      - total euros
     *   [...]
     * ["total"]
     *      - total photos
     *      - total euros
     */
    public function getPricing($user)
    {
        $answer = array(
            "details" => array(),
            "discount" => array(
                "id" => null,
                "title" => null,
                "value" => 0,
                "saving" => 0
            ),
            "overall" => array(
                "quantity" => 0,
                "grossTotal" => 0,
                "total" => 0
        ));

        $formatsQuantities = $this->em->getRepository('CartBundle:Format')->getFormatsQuantity($user);

        if (count($formatsQuantities) === 0)
            return $answer;

        $total = 0;
        $totalQuantity = 0;

        foreach ($formatsQuantities as $formatQuantity)
        {
            $quantity = (int) $formatQuantity['quantityValue'];
            $totalQuantity += $quantity;
            $subTotal = 0;

            $detail = array();
            $detail["format"] = $this->em->getRepository('CartBundle:Format')->find($formatQuantity['formatID']);
            $detail["quantity"] = $quantity;

            if ($quantity === 0)
                continue;

            $pricings = $this->em->getRepository('CartBundle:Price')->getPrices($formatQuantity['formatID']);

            foreach ($pricings as $pricing)
            {
                $priceBreak = $pricing->getQuantity();
                $price = $pricing->getPrice();

                if ($quantity >= $priceBreak)
                {
                    $multiplicateur = floor($quantity / $priceBreak);

                    $subTotal += $multiplicateur * $price;
                    $quantity -= $multiplicateur * $priceBreak;
                }
            }

            $total += $subTotal;

            $detail["total"] = $subTotal;
            $answer["details"][] = $detail;
        }

        $currentDiscount = $this->em->getRepository('CartBundle:Discount')->getCurrentDiscount($total);

        $discount = 0;
        if (!is_null($currentDiscount))
        {
            $answer["discount"]["id"] = $currentDiscount->getId();
            $answer["discount"]["title"] = $currentDiscount->getTitle();
            if (!is_null($currentDiscount->getDiscount()))
                $discount = $currentDiscount->getDiscount();
        }

        $answer["discount"]["value"] = $discount;
        $answer["discount"]["saving"] = $total * $discount / 100;

        $answer["overall"]["quantity"] = $totalQuantity;
        $answer["overall"]["grossTotal"] = $total;
        $answer["overall"]["total"] = $total * (1 - $discount / 100);

        return $answer;
    }

}
