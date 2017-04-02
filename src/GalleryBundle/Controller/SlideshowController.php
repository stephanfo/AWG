<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SlideshowController extends Controller
{

    /**
     * @Route("/slideshow/{page}/{delay}/{imageX}/{imageY}/{imageH}/{endPage}/{sort}", requirements={"page": "\d*", "delay": "\d*", "imageX": "\d*", "imageY": "\d*", "imageH": "\d*", "endPage": "\d*"}, defaults={"page": 1, "delay": 10, "imageX": 4, "imageY": 3, "imageH": 320, "endPage": 0, "sort": "gallery"}, name="slideshow_launcher")
     * @Route("/slideshow/", name="slideshow_launcher_empty")
     */
    public function launcherAction($page, $delay, $imageX, $imageY, $imageH, $endPage, $sort)
    {

        if ($page < 1)
        {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $nbPerPage = $imageX * $imageY;

        switch ($sort) {
            case "gallery":
                $photos = $this
                    ->getDoctrine()
                    ->getRepository('GalleryBundle:Photo')
                    ->getActivePhotos($page, $nbPerPage, false)
                ;
                break;
            case "like":
                $photos = $this
                    ->getDoctrine()
                    ->getRepository('GalleryBundle:Photo')
                    ->getActivePhotos($page, $nbPerPage, true)
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
            'nextPage' => $nextPage,
        ));
    }

    /**
     * @Route("/slideshow/config", name="slideshow_config")
     */
    public function configAction()
    {
        return $this->render('GalleryBundle:Slideshow:config.html.twig');
    }
}
