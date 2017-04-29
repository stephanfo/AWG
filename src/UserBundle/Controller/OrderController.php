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
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class OrderController extends Controller
{

    /**
     * @Route("/order/add", name="order_add_current")
     */
    public function addAction(Request $request)
    {

        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $confirmationForm = $this->getCheckoutForm();
        $confirmationForm->handleRequest($request);
        
        if ($confirmationForm->isSubmitted() && $confirmationForm->isValid())
        {
            $total = $this->get('price_calculator')->getPricing($user);
            $totalCalculated = (float) $total['overall']['total'];

            $resultArray = $confirmationForm->getData();
            $totalSent = (float) $resultArray["total"];

            if (abs($totalSent - $totalCalculated) > 0.001)
            {
                $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans('Votre commande a echoué (la tarification a expiré). Merci de contrôler le total avant de reconfirmer la commande.'));

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

            $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('Votre commande a bien été enregistrée.'));

            $carts = $em->getRepository('CartBundle:Cart')->findBy(array(
                'user' => $user
            ));

            foreach ($carts as $cart)
            {
                $em->remove($cart);
            }

            $em->flush();

            return $this->redirectToRoute('order_list');
        }

        return $this->json(array());
    }

    private function getCheckoutForm()
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
    public function listAction()
    {
        $user = $this->get('user_profile')->getUser();

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
     * @Route("/order/view/{id}", requirements={"id": "\d*"}, name="order_view")
     */
    public function viewAction(Request $request, $id)
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $order = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderDetail($id);

        if (is_null($order))
        {
            $request->getSession()->getFlashBag()->add('warning', $this->get('translator')->trans("Cette commande n'existe pas"));
            return $this->redirectToRoute('order_list');
        }

        if ($order->getUser() !== $user)
        {
            $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans("Vous n'êtes pas autorisé à acceder à cette commande"));
            return $this->redirectToRoute('order_list');
        }

        return $this->render('UserBundle:Order:view.html.twig', array(
                    'order' => $order
        ));
    }

    /**
     * @Route("/order/canceled/{id}", requirements={"id": "\d*"}, name="order_cancel")
     */
    public function canceledAction(Request $request, $id)
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $order = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderUser($id);

        if ($order->getUser() !== $user)
        {
            $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans("Vous n'êtes pas autorisé à modifier cette commande"));
            return $this->redirectToRoute('order_list');
        }

        $order->setCanceled(true);
        $this->getDoctrine()->getManager()->flush();

        $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('Votre commande a bien été annulée.'));

        return $this->redirectToRoute('order_list');
    }

    /**
     * @Route("/order/download/detail/{id}", requirements={"id": "\d*"}, name="order_download_detail")
     */
    public function downloadPhotoAction(Request $request, $id)
    {
        $config = $this->get('app_config')->getConfig();

        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $detail = $this->getDoctrine()->getRepository('CartBundle:Detail')->getDetailPhotoUser($id);

        if (is_null($detail))
        {
            $request->getSession()->getFlashBag()->add('warning', $this->get('translator')->trans("Le detail de la commande n'existe pas"));
            return $this->redirectToRoute('order_list');
        }

        if ($detail->getOrder()->getUser() !== $user)
        {
            $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans("Vous n'êtes pas autorisé à télécharger cette photo"));
            return $this->redirectToRoute('order_list');
        }

        if (!$detail->getOrder()->getPayed() || $detail->getOrder()->getCanceled() || !$config->getApplicationSellFiles())
        {
            $request->getSession()->getFlashBag()->add('danger', $this->get('translator')->trans("Vous n'êtes pas autorisé à télécharger cette photo"));
            return $this->redirectToRoute('order_list');
        }

        $photo = $detail->getPhoto();

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web');

        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');
        $imagePath = $helper->asset($photo, 'imageFile');
        $absoluteImagePath = $webPath . $imagePath;

        if ($config->getApplicationSellFilesForceDownload())
            return $this->file($absoluteImagePath, $photo->getTitle());
        else
            return $this->file($absoluteImagePath, $photo->getTitle(), ResponseHeaderBag::DISPOSITION_INLINE);

    }
}
