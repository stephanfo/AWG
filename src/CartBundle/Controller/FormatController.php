<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Format;
use CartBundle\Form\Type\FormatType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FormatController extends Controller
{

    /**
     * @Route("/formats/add", name="format_add")
     * @Method({"GET", "POST"})
     */
    public function addAction(Request $request)
    {
        $format = new Format();

        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($format);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le format ' . $format->getSize() . ' a été ajouté.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Format:add.html.twig', array(
            'form' => $form->createView(),
            'printersDetails' => $this->getPrinters()
        ));
    }

    /**
     * @Route("/formats/edit/{id}", requirements={"id": "\d*"}, name="format_edit")
     * @Method({"GET", "POST"})
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "id"})
     */
    public function editAction(Format $format, Request $request)
    {
        $form = $this->createForm(FormatType::class, $format);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le format ' . $format->getSize() . ' a été mis à jour.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Format:edit.html.twig', array(
            'form' => $form->createView(),
            'printersDetails' => $this->getPrinters()
        ));
    }

    /**
     * @Route("/formats/delete/{id}", requirements={"id": "\d*"}, name="format_delete")
     * @Method({"GET"})
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "id"})
     */
    public function deleteAction(Format $format, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($format);
        $em->flush();

        $request->getSession()->getFlashBag()->add('warning', 'Le format ' . $format->getSize() . ' a été supprimé');

        return $this->redirectToRoute('tarif_index');
    }

    private function getPrinters()
    {
        $printerProcess = new Process('/usr/bin/lpstat -a');
        $printerProcess->run();

        if (!$printerProcess->isSuccessful()) {
            return $printerProcess->getErrorOutput();
        }

        $printerFullList = explode(PHP_EOL, $printerProcess->getOutput());

        $printerList = array();
        foreach ($printerFullList as $printer) {
            if (preg_match('/^\w+/', $printer) )
            {
                $printerName = explode(' ', $printer);
                $printerList[] = $printerName[0];
            }
        }

        $printersDetails = array();
        foreach ($printerList as $printer)
        {
            $details = array();
            $details['name'] = $printer;

            $optionsProcess = new Process('/usr/bin/lpoptions -p ' . $printer . ' -l');
            $optionsProcess->run();

            if (!$optionsProcess->isSuccessful()) {
                return $optionsProcess->getErrorOutput();
            }

            $details['details'] = nl2br($optionsProcess->getOutput());

            $printersDetails[] = $details;
        }

        return $printersDetails;
    }

}
