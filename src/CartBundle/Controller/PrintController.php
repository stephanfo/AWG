<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use GalleryBundle\Entity\Photo;
use CartBundle\Entity\Format;
use Symfony\Component\Process\Process;

class PrintController extends Controller
{

    /**
     * @Route("/print/single/{photo_id}/{format_id}", name="admin_print_single")
     * @ParamConverter("photo", class="GalleryBundle:Photo", options={"id" = "photo_id"})
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "format_id"})
     */
    public function printSingleAction(Request $request, Photo $photo, Format $format)
    {
        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        $imagePath = $helper->asset($photo, 'imageFile');

        if($photo->getImageWidth() === $photo->getImageHeight() && $format->getPrintSquare() !== "")
            $printCommand = str_replace(array("{quantity}", "{file}"), array(1, $imagePath), $format->getPrintSquare());
        else
            $printCommand = str_replace(array("{quantity}", "{file}"), array(1, $imagePath), $format->getPrint());


        $printProcess = new Process($printCommand);
        $printProcess->run();

        if (!$printProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $printCommand . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
        }
        else {
            $request->getSession()->getFlashBag()->add('success', 'Le commande : ' . $printCommand . ' a réussi (message de confirmation : ' . $printProcess->getOutput() . ').');
        }

        return $this->redirect($request->headers->get('referer'));

    }

    /**
     * @Route("/print/order/{id}", name="admin_print_order")
     */
    public function printOrderContainer(Request $request, $id)
    {
        $formats = $this->getDoctrine()->getRepository('CartBundle:Format')->getOrderDetail($id);

        $error = false;
        $nbPrint = 0;

        foreach ($formats as $format) {

            foreach ($format->getOrderQuantities() as $quantity){

                $photo = $quantity->getDetail()->getPhoto();

                $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
                $imagePath = $helper->asset($photo, 'imageFile');

                if($photo->getImageWidth() === $photo->getImageHeight() && $format->getPrintSquare() !== "")
                    $printCommand = str_replace(array("{quantity}", "{file}"), array($quantity->getQuantity(), $imagePath), $format->getPrintSquare());
                else
                    $printCommand = str_replace(array("{quantity}", "{file}"), array($quantity->getQuantity(), $imagePath), $format->getPrint());

                $nbPrint += $quantity->getQuantity();

                $printProcess = new Process($printCommand);
                $printProcess->run();

                if (!$printProcess->isSuccessful()) {
                    $error = true;
                }
            }
        }

        if($error) {
            $request->getSession()->getFlashBag()->add('danger', "l'impression a échouée");
        }
        else {
            $request->getSession()->getFlashBag()->add('success', 'Toutes les impressions ont été lancées (total : ' . $nbPrint . ')');
        }

        return $this->redirect($request->headers->get('referer'));
    }

}
