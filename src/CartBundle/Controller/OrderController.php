<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\Order;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use CartBundle\Form\Type\ReassignType;
use UserBundle\Form\Type\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class OrderController extends Controller
{

    /**
     * @Route("/order/list", name="admin_order_list")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {
        $filterFormDefault = array (
            'startDate' => new \DateTime('now - 1 day midnight'),
            'stopDate' => new \DateTime('now + 1 day midnight'),
            'status' => 'Ouvertes',
        );

        $filterForm = $this->searchForm();
        $filterForm->handleRequest($request);

        $session = $request->getSession();

        if ($filterForm->isSubmitted() && $filterForm->isValid())
        {
            if(!$filterForm->get('reset')->isClicked())
            {
                $session->set("admin_order_list", $filterForm->getData());
            }
            else
            {
                $filterForm = $this->searchForm();
                $filterForm->setData($filterFormDefault);
                $session->set("admin_order_list", $filterFormDefault);
            }
        }
        else if($session->has("admin_order_list") && !is_null($session->get("admin_order_list")))
        {
            $filterForm = $this->searchForm();
            $filterForm->setData($session->get("admin_order_list"));
        }
        else
        {
            $filterForm = $this->searchForm();
            $filterForm->setData($filterFormDefault);
            $session->set("admin_order_list", $filterFormDefault);
        }

        $resultArray = $filterForm->getData();
        $startDate = $resultArray["startDate"];
        $stopDate = $resultArray["stopDate"];
        $status = $resultArray["status"];

        $orders = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderSearch($startDate, $stopDate, $status);

        return $this->render('CartBundle:Order:index.html.twig', array(
            'orders' => $orders,
            'filterForm' => $filterForm->createView()
        ));
    }

    /**
     * @Route("/order/view/{id}", requirements={"id": "\d*"}, name="admin_order_view")
     * @Method({"GET", "POST"})
     */
    public function viewAction($id, Request $request)
    {
        $order = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderUser($id);

        if (is_null($order))
        {
            $request->getSession()->getFlashBag()->add('warning', "Cette commande n'existe pas");
            return $this->redirectToRoute('admin_order_list');
        }
        
        $reassignForm = $this->createForm(ReassignType::class, $order);
        $reassignForm->handleRequest($request);

        $userManager = $this->get('fos_user.user_manager');
        $user = $order->getUser();

        $currentEmail = $user->getEmail();

        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($reassignForm->isSubmitted() && $reassignForm->isValid())
        {
            $request->getSession()->getFlashBag()->add('success', 'L\'utilisateur a été changé');
            $this->getDoctrine()->getManager()->flush();
        }
        elseif ($userForm->isSubmitted() && $userForm->isValid())
        {
            if($user->getEmail() != $currentEmail && !is_null($userManager->findUserByEmail($user->getEmail())))
            {
                $request->getSession()->getFlashBag()->add('danger', 'L\'adresse e-mail est déjà utilisée par un autre utilisateur');
            }
            else
            {
                $request->getSession()->getFlashBag()->add('success', 'Les données de l\'utilisateur ont été mises à jour');
                $userManager->updateUser($user);
            }
           
        }

        $orderDetails = $this->getDoctrine()->getRepository('CartBundle:Format')->getOrderDetail($id);

        return $this->render('CartBundle:Order:view.html.twig', array(
            'order' => $order,
            'orderDetails' => $orderDetails,
            'reassignForm' => $reassignForm->createView(),
            'userForm' => $userForm->createView(),
        ));
    }

    /**
     * @Route("/order/canceled/{id}", requirements={"id": "\d*"}, name="admin_order_cancel")
     * @Method({"GET"})
     * @ParamConverter("order", class="CartBundle:Order", options={"id" = "id"})
     */
    public function canceledAction(Request $request, Order $order)
    {
        $order->setCanceled(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/order/activate/{id}", requirements={"id": "\d*"}, name="admin_order_activate")
     * @Method({"GET"})
     * @ParamConverter("order", class="CartBundle:Order", options={"id" = "id"})
     */
    public function activateAction(Request $request, Order $order)
    {
        $order->setCanceled(false);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/order/printed/{id}", requirements={"id": "\d*"}, name="admin_order_printed")
     * @Method({"GET"})
     * @ParamConverter("order", class="CartBundle:Order", options={"id" = "id"})
     */
    public function printedAction(Request $request, Order $order)
    {
        $order->setPrinted(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/order/payed/{id}", requirements={"id": "\d*"}, name="admin_order_payed")
     * @Method({"GET"})
     * @ParamConverter("order", class="CartBundle:Order", options={"id" = "id"})
     */
    public function payedAction(Request $request, Order $order)
    {
        $order->setPayed(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    private function searchForm()
    {
        return $this->createFormBuilder()
            ->add('startDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('stopDate', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('status', ChoiceType::class, array(
                'choices' => array(
                    'Ouvertes' => 'Ouvertes',
                    'Terminées' => 'Terminées',
                    'Annulées' => 'Annulées'
                ),
                'placeholder' => 'Toutes',
                'required' => false,
            ))
            ->add('filter', SubmitType::class)
            ->add('reset', SubmitType::class)
            ->getForm();
    }

}
