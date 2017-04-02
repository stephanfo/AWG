<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     */
    public function viewAction()
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $galleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getActiveGalleries();
        $likes = $this->getDoctrine()->getRepository('UserBundle:User')->getUserLikes($user);
        $carts = $this->getDoctrine()->getRepository('UserBundle:User')->getUserCart($user);

        $likesArray = array();
        foreach ($likes->getlikePhotos() as $photo)
        {
            $likesArray[$photo->getId()] = true;
        }

        $cartArray = array();
        foreach ($carts->getcarts() as $cart)
        {
            $cartArray[$cart->getPhoto()->getId()] = true;
        }

        return $this->render('UserBundle:Home:view.salvatorre.html.twig', array(
            'galleries' => $galleries,
            'likes' => $likesArray,
            'carts' => $cartArray
        ));
    }

}
