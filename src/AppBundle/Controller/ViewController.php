<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ViewController extends Controller {

    /**
     * @Route("/intro", name="app_intro")
     * @Method({"GET"})
     */
    public function editAction()
    {
        return $this->render('AppBundle:View:intro.html.twig');    
    }
    
}
