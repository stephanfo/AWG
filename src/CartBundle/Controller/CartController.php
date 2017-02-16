<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class CartController extends Controller {

    /**
     * @Route("/cart/current", name="admin_cart_current")
     */
    public function currentAction()
    {
        
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();
        $priceCalculator = $this->get('price_calculator');

        $usersPrice = array();
        foreach ($users as $key => $user) {
            $usersPrice[$key]['user'] = $user;
            $usersPrice[$key]['price'] = $priceCalculator->getPricing($user);
        }
        
        return $this->render('CartBundle:Cart:current.html.twig', array(
            'usersPrice' => $usersPrice
        ));
    }
    
}