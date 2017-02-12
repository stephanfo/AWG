<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use CartBundle\Entity\Order;
use CartBundle\Entity\Detail;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderController extends Controller
{

    /**
     * @Route("/order/list", name="admin_order_list")
     */
    public function listAction(Request $request)
    {
        $filterForm = $this->searchForm();

        if ($filterForm->handleRequest($request)->isValid())
        {
            $resultArray = $filterForm->getData();
            $startDate = $resultArray["startDate"];
            $stopDate = $resultArray["stopDate"];
            $status = $resultArray["status"];

            $orders = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderSearch($startDate, $stopDate, $status);
        }
        else
        {
            $orders = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderSearch(new \DateTime('now - 1 day midnight'), new \DateTime('now + 1 day midnight'), "Ouvertes");
        }

        return $this->render('CartBundle:Order:index.html.twig', array(
                    'orders' => $orders,
                    'filterForm' => $filterForm->createView()
        ));
    }

    /**
     * @Route("/order/view/{id}", requirements={"id" = "\d*"}, name="admin_order_view")
     */
    public function viewAction($id)
    {
        $orderHeader = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderUser($id);
        $orderDetails = $this->getDoctrine()->getRepository('CartBundle:Format')->getOrderDetail($id);

        return $this->render('CartBundle:Order:view.html.twig', array(
                    'orderHeader' => $orderHeader,
                    'orderDetails' => $orderDetails
        ));
    }

    /**
     * @Route("/order/canceled/{id}", requirements={"id" = "\d*"}, name="admin_order_cancel")
     * @ParamConverter("Order", options={"id" = "order"})
     */
    public function canceledAction(Request $request, Order $order)
    {
        $order->setCanceled(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_order_list');
    }

    /**
     * @Route("/order/printed/{id}", requirements={"id" = "\d*"}, name="admin_order_printed")
     * @ParamConverter("Order", options={"id" = "order"})
     */
    public function printedAction(Request $request, Order $order)
    {
        $order->setPrinted(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_order_list');
    }

    /**
     * @Route("/order/payed/{id}", requirements={"id" = "\d*"}, name="admin_order_payed")
     * @ParamConverter("Order", options={"id" = "order"})
     */
    public function payedAction(Request $request, Order $order)
    {
        $order->setPayed(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_order_list');
    }

    private function searchForm()
    {
        return $this->createFormBuilder()
                        ->add('startDate', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'dd-MM-yyyy',
                            'data' => new \DateTime('now - 1 day')
                        ))
                        ->add('stopDate', DateType::class, array(
                            'widget' => 'single_text',
                            'format' => 'dd-MM-yyyy',
                            'data' => new \DateTime('now + 1 day')
                        ))
                        ->add('status', ChoiceType::class, array(
                            'choices' => array(
                                'Ouvertes' => 'Ouvertes',
                                'Terminées' => 'Terminées',
                                'Annulées' => 'Annulées'
                            ),
                            'placeholder' => 'Toutes',
                            'required' => false,
                            'data' => 'Ouvertes'
                        ))
                        ->add('submit', SubmitType::class)
                        ->getForm();
    }

}
