<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SlideshowController extends Controller
{

    /**
     * @Route("/slideshow/{page}/{delay}/{imageX}/{imageY}/{imageH}/{endPage}/{sort}/{gallery}", requirements={"page": "\d*", "delay": "\d*", "imageX": "\d*", "imageY": "\d*", "imageH": "\d*", "endPage": "\d*", "gallery": "\d+"}, name="slideshow_launcher")
     * @Route("/slideshow/", name="slideshow_launcher_empty")
     * @Method({"GET"})
     */
    public function launcherAction($page, $delay, $imageX, $imageY, $imageH, $endPage, $sort, $gallery)
    {

        if ($page < 1)
        {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $nbPerPage = $imageX * $imageY;

        if($gallery == 0)
            $galleryId = null;
        else
            $galleryId = $gallery;

        switch ($sort) {
            case "gallery":
                $photos = $this
                    ->getDoctrine()
                    ->getRepository('GalleryBundle:Photo')
                    ->getActivePhotos($page, $nbPerPage, false, $galleryId)
                ;
                break;
            case "like":
                $photos = $this
                    ->getDoctrine()
                    ->getRepository('GalleryBundle:Photo')
                    ->getActivePhotos($page, $nbPerPage, true, $galleryId)
                ;
                break;
            default:
                $photos = $this
                    ->getDoctrine()
                    ->getRepository('GalleryBundle:Photo')
                    ->getActivePhotos($page, $nbPerPage)
                ;
                break;
        }

        $nbPages = ceil(count($photos) / $nbPerPage);

        if ($page > $nbPages && $page > 1)
        {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        if ($endPage > 0)
            if ($endPage > $nbPages)
                $endPage = $nbPages;
            else
                $nbPages = $endPage;

        if ($page < $nbPages)
            $nextPage = $page + 1;
        else
            $nextPage = 1;

        return $this->render('GalleryBundle:Slideshow:launcher.html.twig', array(
            'photos' => $photos->getIterator(),
            'nbTotalPhotos' => count($photos),
            'nbPages' => $nbPages,
            'page' => $page,
            'delay' => $delay,
            'imageX' => $imageX,
            'imageY' => $imageY,
            'imageH' => $imageH,
            'endPage' => $endPage,
            'sort' => $sort,
            'gallery' => $gallery,
            'nextPage' => $nextPage,
        ));
    }

    /**
     * @Route("/slideshow/config", name="slideshow_config")
     * @Method({"GET"})
     */
    public function configAction()
    {
        $listGalleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->findAll(array(), array('date' => "DESC"));

        return $this->render('GalleryBundle:Slideshow:config.html.twig', array(
            'listGalleries' => $listGalleries,
        ));
    }
}
