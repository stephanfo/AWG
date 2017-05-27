<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     * @Route("/{oneGallery}", name="home_one_gallery")
     */
    public function viewAction($oneGallery = null)
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $config = $this->get('app_config')->getConfig();

        if($config->getGallerySingleGallery())
        {
            if (is_null($oneGallery))
            {
                $lastGallery = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getLastActiveGalleries();

                if(!is_null($lastGallery))
                {
                    $oneGallery = $lastGallery->getId();
                    $galleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getActiveGalleriesDetail($oneGallery);
                }
                else
                {
                    $galleries = null;
                }
            }
            else
            {
                $galleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getActiveGalleriesDetail($oneGallery);

            }
            $listGalleriesAndCount = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getActiveGalleriesAndPhotoCount();
        }
        else
        {
            $galleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getActiveGalleriesDetail();
            $listGalleriesAndCount = null;
        }
        
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
            'carts' => $cartArray,
            'listGalleriesAndCount' => $listGalleriesAndCount,
            'oneGallery' => $oneGallery
        ));
    }

}
