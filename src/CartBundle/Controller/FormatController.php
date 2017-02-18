<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Format;
use CartBundle\Form\Type\FormatType;
use Symfony\Component\HttpFoundation\Request;

class FormatController extends Controller
{

    /**
     * @Route("/formats/add", name="format_add")
     */
    public function addAction(Request $request)
    {
        $format = new Format();

        $form = $this->createForm(FormatType::class, $format);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($format);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le format ' . $format->getSize() . ' a été ajouté.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Format:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/formats/edit/{id}", requirements={"id" = "\d*"}, name="format_edit")
     * @ParamConverter("Format", options={"id" = "format"})
     */
    public function editAction(Format $format, Request $request)
    {
        $form = $this->createForm(FormatType::class, $format);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le format ' . $format->getSize() . ' a été mis à jour.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Format:edit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/formats/delete{id}", requirements={"id" = "\d*"}, name="format_delete")
     * @ParamConverter("Format", options={"id" = "format"})
     */
    public function deleteAction(Format $format, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($format);
        $em->flush();

        $request->getSession()->getFlashBag()->add('warning', 'Le format ' . $format->getSize() . ' a été supprimé');

        return $this->redirectToRoute('tarif_index');
    }

}
