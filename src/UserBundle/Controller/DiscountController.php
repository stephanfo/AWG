<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DiscountController extends Controller
{

    public function bottomBarAction()
    {
        $discount = $this->getDoctrine()->getRepository('CartBundle:Discount')->getCurrentDiscount();

        if (is_null($discount))
            return new Response("");

        $now = new \DateTime('now');
        $countDownSeconds = $discount->getStopTime();

        if (is_null($countDownSeconds))
            return new Response("");

        return $this->render('UserBundle:Discount:bottombar.html.twig', array(
                    'discount' => $discount,
                    'expire' => $countDownSeconds->getTimestamp() - $now->getTimestamp()
        ));
    }

    /**
     * @Route("/offers", name="offers")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $formats = $em->getRepository('CartBundle:Format')->getAllPrices();
        $discounts = $em->getRepository('CartBundle:Discount')->getAllCurrentDiscount();

        return $this->render('UserBundle:Discount:list.html.twig', array(
                    'formats' => $formats,
                    'discounts' => $discounts
        ));
    }

}
