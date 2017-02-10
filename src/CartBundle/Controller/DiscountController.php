<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Discount;
use CartBundle\Form\DiscountType;
use Symfony\Component\HttpFoundation\Request;

class DiscountController extends Controller
{

    /**
     * @Route("/discounts/add", name="discount_add")
     */
    public function addAction(Request $request)
    {
        $discount = new Discount();
        $discount->setStartTime(new \DateTime('now'));
        $discount->setStopTime(new \DateTime('now + 1 hour'));

        $form = $this->createForm(DiscountType::class, $discount);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($discount);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le discount ' . $discount->getTitle() . ' a été ajouté.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Discount:add.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/discounts/edit/{id}", requirements={"id" = "\d*"}, name="discount_edit")
     * @ParamConverter("Discount", options={"id" = "discount"})
     */
    public function editAction(Discount $discount, Request $request)
    {
        $form = $this->createForm(DiscountType::class, $discount);

        if ($form->handleRequest($request)->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Le discount ' . $discount->getTitle() . ' a été mis à jour.');

            return $this->redirectToRoute('tarif_index');
        }

        return $this->render('CartBundle:Discount:edit.html.twig', array(
                    'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/discounts/delete/{id}", requirements={"id" = "\d*"}, name="discount_delete")
     * @ParamConverter("Discount", options={"id" = "discount"})
     */
    public function deleteAction(Discount $discount)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($discount);
        $em->flush();

        return $this->redirectToRoute('tarif_index');
    }

    /**
     * @Route("/discounts/active/toggle/{id}", requirements={"id" = "\d*"}, name="discount_active_toggle")
     * @ParamConverter("Discount", options={"id" = "discount"})
     */
    public function activeToggleAction(Discount $discount)
    {
        $em = $this->getDoctrine()->getManager();

        if($discount->getActive())
            $discount->setActive (false);
        else
            $discount->setActive (true);

        $em->flush();

        return $this->redirectToRoute('tarif_index');
    }

}
