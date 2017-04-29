<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\Type\ConfigType;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends Controller {

    /**
     * @Route("/config/edit", name="app_config_edit")
     */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $config = $this->get('app_config')->getConfig();
        
        if (is_null($config))
            throw $this->createNotFoundException("La configuration est morte. Il faut recharger les fixtures");

        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'La configuration a été mise à jour.');

            return $this->redirectToRoute('app_config_edit');
        }

        return $this->render('AppBundle:Config:edit.html.twig', array(
            'form' => $form->createView(),
        ));    
    }
    
}
