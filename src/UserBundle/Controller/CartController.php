<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GalleryBundle\Entity\Photo;
use CartBundle\Entity\Cart;
use CartBundle\Entity\Format;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use CartBundle\Entity\CartQuantity;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class CartController extends Controller
{

    /**
     * @Route("/cart", name="cart")
     */
    public function cartAction()
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $em = $this->getDoctrine()->getManager();

        $cart = $em->getRepository('UserBundle:User')->getUserCart($user);
        $formats = $em->getRepository('CartBundle:Format')->findAll();

        $cartFormat = $em->getRepository('CartBundle:Cart')->getCartFormat($user);

        $cartFormatArray = array();
        foreach ($cartFormat as $cartLine)
        {
            foreach ($cartLine->getQuantities() as $quantity)
            {
                $cartFormatArray[$cartLine->getId()][$quantity->getFormat()->getId()] = $quantity->getQuantity();
            }
        }

        $total = $this->get('price_calculator')->getPricing($user);

        return $this->render('UserBundle:Cart:view.html.twig', array(
                    'userCarts' => $cart,
                    'formats' => $formats,
                    'cartFormat' => $cartFormatArray,
                    'total' => $total
        ));
    }

    /**
     * @Route("/cart/ajax/toggle/{id}", requirements={"id" = "\d*"}, name="cart_toggle")
     * @Route("/cart/ajax/toggle/", name="cart_toggle_empty_link")
     */
    public function toogleCartAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('user_profile')->getUser();

        $cart = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        if (is_null($cart))
        {
            $cart = new Cart();
            $cart->setPhoto($photo);
            $cart->setUser($user);
            $em->persist($cart);
            $em->flush();
            return $this->json(array(
                        "id" => $photo->getId(),
                        "cart" => true
            ));
        }
        else
        {
            $em->remove($cart);
            $em->flush();
            return $this->json(array(
                        "id" => $photo->getId(),
                        "cart" => false
            ));
        }
    }

    /**
     * @Route("/cart/ajax/delete/{id}", requirements={"id" = "\d*"}, name="cart_delete")
     */
    public function deleteCartAction(Photo $photo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('user_profile')->getUser();

        $cart = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        if (!is_null($cart))
        {
            $em->remove($cart);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La photo ' . $photo->getTitle() . ' a bien été retiré du panier.');
        }
        else
            $request->getSession()->getFlashBag()->add('danger', 'La photo ' . $photo->getTitle() . ' est introuvable dans votre panier');


        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/ajax/delete/all", name="cart_delete_all")
     */
    public function deleteAllCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('user_profile')->getUser();

        $carts = $em->getRepository('CartBundle:Cart')->findBy(array(
            'user' => $user
        ));

        foreach ($carts as $cart)
        {
            $em->remove($cart);
        }
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Votre panier a bien été vidé.');

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/ajax/update/{photo}/{format}/{quantity}", requirements={"photo" = "\d*", "format" = "\d*", "quantity" = "\d*"}, name="cart_update")
     * @Route("/cart/ajax/update/", name="cart_update_empty_link")
     * @ParamConverter("photo", class="GalleryBundle:Photo", options={"id" = "photo"})
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "format"})
     */
    public function updateCartAction(Photo $photo, Format $format, $quantity)
    {
        $user = $this->get('user_profile')->getUser();

        $em = $this->getDoctrine()->getManager();

        $cartLine = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        $quantityLine = $em->getRepository('CartBundle:CartQuantity')->findOneBy(array(
            'cart' => $cartLine,
            'format' => $format
        ));

        if (is_null($quantityLine))
        {
            $quantityLine = new CartQuantity;
            $quantityLine->setFormat($format);
            $quantityLine->setCart($cartLine);
            $quantityLine->setQuantity($quantity);
            $em->persist($quantityLine);
            $em->flush();
        }
        else
        {
            $quantityLine->setQuantity($quantity);
            $em->flush();
        }

        $pricing = $this->get('price_calculator')->getPricing($user);
        $total = $pricing["overall"]["total"];

        return $this->json(array(
                    "photo" => $photo->getId(),
                    "format" => $format->getId(),
                    "quantity" => $quantityLine->getQuantity(),
                    "total" => $total
        ));
    }

    /**
     * @Route("/cart/checkout", name="checkout")
     */
    public function checkoutAction()
    {
        $user = $this->get('user_profile')->getUser();

        if (is_null($user))
            return $this->redirectToRoute('user_add');

        $total = $this->get('price_calculator')->getPricing($user);

        $confirmationForm = $this->getCheckoutForm($total['overall']['total']);

        return $this->render('UserBundle:Cart:checkout.html.twig', array(
                    'total' => $total,
                    'form' => $confirmationForm->createView()
        ));
    }

    private function getCheckoutForm($total)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('order_add_current'))
                        ->add("total", HiddenType::class, array(
                            'data' => $total,
                        ))
                        ->getForm()
        ;
    }

}
