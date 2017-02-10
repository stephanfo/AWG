<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use CartBundle\Entity\Order;
use CartBundle\Entity\Detail;
use CartBundle\Entity\OrderQuantity;

class OrderController extends Controller
{

    /**
     * @Route("/order/add", name="order_add_current")
     */
    public function addAction(Request $request)
    {

        $user = $this->container->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $confirmationForm = $this->getCheckoutForm();

        if ($confirmationForm->handleRequest($request)->isValid())
        {
            $total = $this->container->get('price_calculator')->getPricing($user);
            $totalCalculated = (float) $total['overall']['total'];

            $resultArray = $confirmationForm->getData();
            $totalSent = (float) $resultArray["total"];

            if (abs($totalSent - $totalCalculated) > 0.001)
            {
                $request->getSession()->getFlashBag()->add('danger', 'Votre commande a echoué (la tarification a expiré). Merci de contrôler le total avant de reconfirmer la commande.');

                return $this->redirectToRoute('checkout');
            }

            $em = $this->getDoctrine()->getManager();
            $carts = $em->getRepository('CartBundle:Cart')->getCartForOrder($user);

            $order = new Order();
            $order->setUser($user);
            $order->setDiscountTitle($total['discount']['title']);
            $order->setDiscountValue($total['discount']['value']);
            $order->setDiscountSaving($total['discount']['saving']);
            $order->setGrossTotal($total['overall']['grossTotal']);
            $order->setTotal($total['overall']['total']);
            $order->setQuantity($total['overall']['quantity']);

            $em->persist($order);

            foreach ($carts as $cart)
            {
                $detail = new Detail();
                $detail->setPhoto($cart->getPhoto());
                $detail->setOrder($order);
                $em->persist($detail);

                foreach ($cart->getQuantities() as $cartQuantity)
                {
                    $quantity = new OrderQuantity();
                    $quantity->setQuantity($cartQuantity->getQuantity());
                    $quantity->setFormat($cartQuantity->getFormat());
                    $quantity->setDetail($detail);

                    $em->persist($quantity);
                }
            }

            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Votre commande a bien été enregistrée.');

            return $this->redirectToRoute('order_list');
        }

        return $this->json(array());
    }

    public function getCheckoutForm()
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('order_add_current'))
                        ->add("total", HiddenType::class)
                        ->getForm()
        ;
    }

    /**
     * @Route("/order/list", name="order_list")
     */
    public function listAction(Request $request)
    {
        $user = $this->container->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $orders = $this->getDoctrine()->getRepository('CartBundle:Order')->findBy(
                array(
            'user' => $user
                ), array(
            'updated' => "DESC"
                )
        );

        return $this->render('UserBundle:Order:index.html.twig', array(
                    'orders' => $orders
        ));
    }

    /**
     * @Route("/order/view/{id}", requirements={"id" = "\d*"}, name="order_view")
     */
    public function viewAction($id)
    {
        $order = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderDetail($id);

        return $this->render('UserBundle:Order:view.html.twig', array(
                    'order' => $order
        ));
    }

    /**
     * @Route("/order/canceled/{id}", requirements={"id" = "\d*"}, name="order_cancel")
     * @ParamConverter("Order", options={"id" = "order"})
     */
    public function canceledAction(Request $request, Order $order)
    {
        $order->setCanceled(true);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', 'Votre commande a bien été annulée.');

        return $this->redirectToRoute('order_list');
    }

}
