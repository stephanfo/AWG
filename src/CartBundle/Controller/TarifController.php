<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CartBundle\Form\Type\FormatType;
use CartBundle\Entity\Format;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class TarifController extends Controller
{

    /**
     * @Route("/tarifs/list", name="tarif_index")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $formats = $em->getRepository('CartBundle:Format')->getAllFormatPriceDiscount();
        $discounts = $em->getRepository('CartBundle:Discount')->findAll();

        $format = new Format();
        $formFormat = $this->createForm(FormatType::class, $format, array(
            'action' => $this->generateUrl('format_add')
        ));

        return $this->render('CartBundle:Tarif:index.html.twig', array(
                    'formats' => $formats,
                    'discounts' => $discounts,
                    'formFormat' => $formFormat->createView()
        ));
    }

}
