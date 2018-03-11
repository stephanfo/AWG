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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CartController extends Controller
{

    /**
     * @Route("/cart", name="cart")
     * @Method({"GET"})
     */
    public function cartAction()
    {
        $user = $this->getUser();

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
     * @Route("/cart/ajax/toggle/{id}", requirements={"id": "\d*"}, name="cart_toggle")
     * @Route("/cart/ajax/toggle/", name="cart_toggle_empty_link")
     * @Method({"GET"})
     */
    public function toogleCartAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

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
     * @Route("/cart/ajax/delete/{id}", requirements={"id": "\d*"}, name="cart_delete")
     * @Method({"GET"})
     */
    public function deleteCartAction(Photo $photo, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $cart = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        if (!is_null($cart))
        {
            $em->remove($cart);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('La photo %title% a bien été retirée du panier.', array('%title%' => $photo->getTitle())));
        }

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/ajax/delete/all", name="cart_delete_all")
     * @Method({"GET"})
     */
    public function deleteAllCartAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $carts = $em->getRepository('CartBundle:Cart')->findBy(array(
            'user' => $user
        ));

        foreach ($carts as $cart)
        {
            $em->remove($cart);
        }
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', $this->get('translator')->trans('Votre panier a bien été vidé.'));

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/ajax/quantities/{photo}", requirements={"photo": "\d*"}, name="cart_quantities")
     * @Route("/cart/ajax/quantities/", name="cart_quantities_empty_link")
     * @Method({"GET"})
     * @ParamConverter("photo", class="GalleryBundle:Photo", options={"id" = "photo"})
     */
    public function formatsQuantityAction(Photo $photo)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cartLine = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        $formatsQuantities = array();
        if (count($cartLine) > 0)
        {
            foreach ($cartLine->getQuantities() as $quantity){
                $formatsQuantities[$quantity->getFormat()->getId()] = $quantity->getQuantity();
            }

        }

        return $this->json(array(
            "photo" => $photo->getId(),
            "formatsQuantities" => $formatsQuantities,
        ));
    }

    /**
     * @Route("/cart/ajax/update/{photo}/{format}/{quantity}", requirements={"photo": "\d*", "format": "\d*", "quantity": "\d*"}, name="cart_update")
     * @Route("/cart/ajax/update/", name="cart_update_empty_link")
     * @Method({"GET"})
     * @ParamConverter("photo", class="GalleryBundle:Photo", options={"id" = "photo"})
     * @ParamConverter("format", class="CartBundle:Format", options={"id" = "format"})
     */
    public function updateCartAction(Photo $photo, Format $format, $quantity)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $cartLine = $em->getRepository('CartBundle:Cart')->findOneBy(array(
            'photo' => $photo,
            'user' => $user
        ));

        if (is_null($cartLine))
        {
            $cartLine = new Cart();
            $cartLine->setUser($user);
            $cartLine->setPhoto($photo);
            $em->persist($cartLine);
            $em->flush();
        }

        $quantityLine = $em->getRepository('CartBundle:CartQuantity')->findOneBy(array(
            'cart' => $cartLine,
            'format' => $format
        ));

        if (is_null($quantityLine))
        {
            if ($quantity > 0)
            {
                $quantityLine = new CartQuantity;
                $quantityLine->setFormat($format);
                $quantityLine->setQuantity($quantity);
                $quantityLine->setCart($cartLine);
                $em->persist($quantityLine);
                $em->flush();

                $cartLine->addQuantity($quantityLine);
            }
        }
        else
        {
            if ($quantity > 0)
            {
                $quantityLine->setQuantity($quantity);
            }
            else
            {
                $em->remove($quantityLine);
            }
            $em->flush();
        }

        $inCart = true;
        if (count($cartLine->getQuantities()) === 0)
        {
            $inCart = false;
            $em->remove($cartLine);
            $em->flush();
        }

        $pricing = $this->get('price_calculator')->getPricing($user);
        $total = $pricing["overall"]["total"];

        return $this->json(array(
            "photo" => $photo->getId(),
            "format" => $format->getId(),
            "quantity" => $quantity,
            "total" => $total,
            "inCart" => $inCart
        ));
    }

    /**
     * @Route("/cart/checkout", name="checkout")
     * @Method({"GET"})
     */
    public function checkoutAction()
    {
        $user = $this->getUser();

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
