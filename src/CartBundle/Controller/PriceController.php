<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Price;
use CartBundle\Entity\Format;
use CartBundle\Form\PriceType;
use Symfony\Component\HttpFoundation\Request;

class PriceController extends Controller
{

    /**
     * @Route("/prices/add/format/{id}", requirements={"id" = "\d*"}, name="price_add")
     * @ParamConverter("Format", options={"id" = "format"})
     */
    public function addAction(Format $format, Request $request)
    {
        $price = new Price();
        $price->setFormat($format);

        $form = $this->createForm(PriceType::class, $price);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($price);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le prix a été ajouté.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Price:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/prices/edit/{id}", requirements={"id" = "\d*"}, name="price_edit")
     * @ParamConverter("Price", options={"id" = "price"})
     */
    public function editAction(Price $price, Request $request)
    {
        $form = $this->createForm(PriceType::class, $price);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le prix a été mis à jour.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Price:edit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/prices/delete/{id}", requirements={"id" = "\d*"}, name="price_delete")
     * @ParamConverter("Price", options={"id" = "price"})
     */
    public function deleteAction(Price $price)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($price);
        $em->flush();

        return $this->redirectToRoute('tarif_index');
    }

}
