<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Form\Type\UserType;

class UserController extends Controller
{

    /**
     * @Route("/user/list", name="app_user_list")
     */
    public function indexAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $userList = $userManager->findUsers();

        foreach ($userList as $key => $user)
        {
            if($user->hasRole('ROLE_SUPER_ADMIN'))
                unset($userList[$key]);
        }
        
        return $this->render('AppBundle:User:index.html.twig', array(
            'userList' => $userList,
        ));
    }

    /**
     * @Route("/user/edit/{id}", requirements={"id": "\d*"}, name="app_user_edit")
     */
    public function editAction($id, Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        $currentEmail = $user->getEmail();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if($user->getEmail() != $currentEmail && !is_null($userManager->findUserByEmail($user->getEmail())))
            {
                $request->getSession()->getFlashBag()->add('danger', 'L\'adresse e-mail est déjà utilisée par un autre utilisateur');
            }
            else
            {
                $request->getSession()->getFlashBag()->add('success', 'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a été mis à jour.');
                $userManager->updateUser($user);

                return $this->redirectToRoute('app_user_list');
            }

        }

        return $this->render('AppBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/user/toggle/enable/{id}", requirements={"id": "\d*"}, name="app_user_toggle_enable")
     */
    public function toggleEnableAction($id, Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        if($user->isEnabled())
        {
            $user->setEnabled(false);
            $request->getSession()->getFlashBag()->add('success', 'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a été désactivé');
        }
        else
        {
            $user->setEnabled(true);
            $request->getSession()->getFlashBag()->add('success', 'L\'utilisateur ' . $user->getFirstname() . ' ' . $user->getLastname() . ' a été activé');
        }
        
        $userManager->updateUser($user);
        
        return $this->redirectToRoute('app_user_list');
    }
}
