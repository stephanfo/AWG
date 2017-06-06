<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class HomeController extends Controller
{

    /**
     * @Route("/", name="home")
     * @Route("/{oneGallery}", requirements={"oneGallery": "\d*"}, name="home_one_gallery")
     * @Method({"GET"})
     */
    public function viewAction($oneGallery = null)
    {
        $config = $this->get('app_config')->getConfig();

        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $user = $this->getUser();

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
        }
        else
        {
            if($config->getGalleryAnonymousAccess())
            {
                $likesArray = null;
                $cartArray = null;
            }
            else
            {
                return $this->redirectToRoute('fos_user_registration_register');
            }
        }

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

        return $this->render('UserBundle:Home:view.salvatorre.html.twig', array(
            'galleries' => $galleries,
            'likes' => $likesArray,
            'carts' => $cartArray,
            'listGalleriesAndCount' => $listGalleriesAndCount,
            'oneGallery' => $oneGallery
        ));
    }

}
