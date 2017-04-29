<?php

namespace GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GalleryBundle\Entity\Gallery;
use GalleryBundle\Entity\Photo;

class PhotoController extends Controller
{

    /**
     * @Route("/photo/loader/{id}", requirements={"id": "\d*"}, name="photo_loader")
     */
    public function addAction(Gallery $gallery)
    {
        return $this->render('GalleryBundle:Photo:loader.html.twig', array(
            'gallery' => $gallery,
            'maxFileSize' => ini_get('upload_max_filesize'),
            'phpIniFile' => php_ini_loaded_file()
        ));
    }

    /**
     * @Route("/photo/add/{id}", requirements={"id": "\d*"}, name="photo_add")
     */
    public function loaderAction(Request $request, Gallery $gallery)
    {
        $file = $request->files->get('file');

        if (is_null($file))
            return $this->redirectToRoute('photo_loader', array("id" => $gallery->getId()));

        $extension = $file->guessExtension();

        if ($extension !== "jpeg")
            return new Response("Seul les fichiers jpg et png sont autorisés (pas les " . $extension . ").", Response::HTTP_BAD_REQUEST);

        $size = getimagesize($file);

        $em = $this->getDoctrine()->getManager();

        $photo = new Photo();

        $photo->setImageWidth($size[0]);
        $photo->setImageHeight($size[1]);
        $photo->setGallery($gallery);
        $photo->setImageFile($file);
        $photo->setTitle($file->getClientOriginalName());
        $photo->setDoNotCrop(false);

        $em->persist($photo);
        $em->flush();

        return new Response("Photo téléchargée et chargée en base");
    }

    /**
     * @Route("/photo/clear/{id}", requirements={"id": "\d*"}, name="photo_clear")
     */
    public function clearAction(Gallery $gallery, Request $request)
    {
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            foreach ($gallery->getPhotos() as $photo)
            {
                $em->remove($photo);
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'La galerie ' . $gallery->getTitle() . ' a été vidée de ses photos.');

            return $this->redirectToRoute('gallery_index');
        }

        return $this->render('GalleryBundle:Photo:delete.html.twig', array(
            'form' => $form->createView(),
            'gallery' => $gallery
        ));
    }

    /**
     * @Route("/photo/delete/{id}", requirements={"id": "\d*"}, name="photo_delete")
     */
    public function deleteAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($photo);
        $em->flush();

        return $this->redirectToRoute('gallery_view', array('id' => $photo->getGallery()->getId()));
    }

    /**
     * @Route("/photo/crop/toggle/{id}", requirements={"id": "\d*"}, name="photo_toogle_crop")
     */
    public function cropToggleAction(Photo $photo, Request $request)
    {
        if ($photo->getDoNotCrop())
            $photo->setDoNotCrop(false);
        else
            $photo->setDoNotCrop(true);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
