<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GalleryBundle\Entity\Gallery;
use Symfony\Component\HttpFoundation\Request;
use GalleryBundle\Form\Type\GalleryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class GalleryController extends Controller
{

    /**
     * @Route("/list/{page}", requirements={"page": "\d*"}, defaults={"page": 1}, name="gallery_index")
     * @Method({"GET"})
     */
    public function indexAction($page)
    {
        if ($page < 1)
        {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        $nbPerPage = 25;

        $listGalleries = $this->getDoctrine()
            ->getManager()
            ->getRepository('GalleryBundle:Gallery')
            ->getGalleriesPaginator($page, $nbPerPage)
        ;

        $nbPages = ceil(count($listGalleries) / $nbPerPage);

        if ($page > $nbPages && $page > 1)
        {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        return $this->render('GalleryBundle:Gallery:index.html.twig', array(
            'listGalleries' => $listGalleries->getIterator(),
            'totalListGalleries' => count($listGalleries),
            'nbPages' => $nbPages,
            'page' => $page
        ));
    }

    /**
     * @Route("/view/{id}", requirements={"id": "\d*"}, name="gallery_view")
     * @Method({"GET"})
     */
    public function viewAction($id)
    {
        $gallery = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository("GalleryBundle:Gallery")
            ->getGalleryDetail($id)
        ;

        if (is_null($gallery))
            throw $this->createNotFoundException("La galerie " . $id . " n'existe pas.");

        return $this->render('GalleryBundle:Gallery:view.salvatorre.html.twig', array(
            'gallery' => $gallery,
        ));
    }

    /**
     * @Route("/thumbs/{id}", requirements={"id": "\d*"}, name="gallery_thumbs")
     * @Method({"GET"})
     */
    public function thumbsAction($id)
    {
        $gallery = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository("GalleryBundle:Gallery")
            ->getGalleryDetail($id)
        ;

        if (is_null($gallery))
            throw $this->createNotFoundException("La galerie " . $id . " n'existe pas.");

        return $this->render('GalleryBundle:Gallery:thumbs.html.twig', array(
            'gallery' => $gallery,
        ));
    }

    /**
     * @Route("/add", name="gallery_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $gallery = new Gallery;
        $gallery->setDate(new \DateTime('now'));

        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gallery);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La galerie ' . $gallery->getTitle() . ' a été créé.');

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('GalleryBundle:Gallery:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d*"}, name="gallery_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Gallery $gallery, Request $request)
    {
        if (is_null($gallery))
            throw $this->createNotFoundException("La galerie " . $gallery->getId() . " n'existe pas.");

        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            $request->getSession()->getFlashBag()->add('success', 'La galerie ' . $gallery->getTitle() . ' a été mise à jour.');

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('GalleryBundle:Gallery:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d*"}, name="gallery_delete")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Gallery $gallery, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($gallery);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'La galerie ' . $gallery->getTitle() . ' a été supprimée.');

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('GalleryBundle:Gallery:delete.html.twig', array(
            'form' => $form->createView(),
            'gallery' => $gallery
        ));
    }

    /**
     * @Route("/toggle/{id}", requirements={"id": "\d*"}, name="gallery_toggle")
     * @Method({"GET"})
     */
    public function toggleAction(Gallery $gallery, Request $request)
    {

        if ($gallery->getActive())
        {
            $gallery->setActive(false);
            $request->getSession()->getFlashBag()->add('success', "La galerie " . $gallery->getTitle() . " a été désactivée");
        }
        else
        {
            $gallery->setActive(true);
            $request->getSession()->getFlashBag()->add('success', "La galerie " . $gallery->getTitle() . " a été activée");
        }

        $this->getDoctrine()->getManager()->flush();


        if (is_null($request->headers->get('referer')))
            return $this->redirectToRoute('gallery_index');

        return $this->redirect($request->headers->get('referer'));
    }

}
