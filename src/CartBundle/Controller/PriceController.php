<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Price;
use CartBundle\Entity\Format;
use CartBundle\Form\Type\PriceType;
use Symfony\Component\HttpFoundation\Request;

class PriceController extends Controller
{

    /**
     * @Route("/prices/add/format/{id}", requirements={"id": "\d*"}, name="price_add")
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "id"})
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
     * @Route("/prices/edit/{id}", requirements={"id": "\d*"}, name="price_edit")
     * @ParamConverter("price", class="CartBundle:Price", options={"id" = "id"})
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
     * @Route("/prices/delete/{id}", requirements={"id": "\d*"}, name="price_delete")
     * @ParamConverter("price", class="CartBundle:Price", options={"id" = "id"})
     */
    public function deleteAction(Price $price, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($price);
        $em->flush();

        $request->getSession()->getFlashBag()->add('warning', 'Le prix a été supprimé.');

        return $this->redirectToRoute('tarif_index');
    }

}
