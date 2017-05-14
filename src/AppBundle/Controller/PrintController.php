<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;

class PrintController extends Controller
{

    /**
     * @Route("/print/list", name="app_print_index")
     */
    public function indexAction(Request $request)
    {

        $command = "/usr/bin/lpstat";

        $jobProcess = new Process($command);
        $jobProcess->run();

        if (!$jobProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $command . ' a échouée (message d\'erreur : '. $jobProcess->getErrorOutput() . ').');
        }

        $jobFullList = explode(PHP_EOL, $jobProcess->getOutput());

        $jobList = array();
        foreach ($jobFullList as $job) {
            if (preg_match('/^\w+/', $job) )
            {
                $jobName = explode(' ', $job);
                $jobList[] = $jobName[0];
            }
        }

        return $this->render('AppBundle:Print:index.html.twig', array(
            'raw' => $jobList,
            'jobList' => $jobList,
        ));
    }
    
    /**
     * @Route("/print/delete/all", name="app_print_delete_all")
     */
    public function deleteAllAction(Request $request)
    {
        $command = "/usr/bin/lpstat";

        $jobProcess = new Process($command);
        $jobProcess->run();

        if (!$jobProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $command . ' a échouée (message d\'erreur : '. $jobProcess->getErrorOutput() . ').');
        }

        $jobFullList = explode(PHP_EOL, $jobProcess->getOutput());

        foreach ($jobFullList as $job) {
            if (preg_match('/^\w+/', $job) )
            {
                $jobName = explode(' ', $job);

                $command = "/usr/bin/cancel " . $jobName[0];

                $jobProcess = new Process($command);
                $jobProcess->run();

                if (!$jobProcess->isSuccessful()) {
                    $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $command . ' a échouée (message d\'erreur : '. $jobProcess->getErrorOutput() . ').');
                }
            }
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/print/delete/{job}", requirements={"job": "[\w-]+"}, name="app_print_delete")
     */
    public function deleteAction(Request $request, $job)
    {
        $command = "/usr/bin/cancel " . $job;

        $jobProcess = new Process($command);
        $jobProcess->run();

        if (!$jobProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $command . ' a échouée (message d\'erreur : '. $jobProcess->getErrorOutput() . ').');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('success', 'L\'impression ' . $job . ' a bien été supprimée.');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/print/restart", name="app_print_restart")
     */
    public function restartAction(Request $request)
    {
        $command = "/usr/bin/sudo /etc/init.d/cups restart";

        $jobProcess = new Process($command);
        $jobProcess->run();

        if (!$jobProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('danger', 'Le commande : ' . $command . ' a échouée (message d\'erreur : '. $jobProcess->getErrorOutput() . ').');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('success', 'Le serveur d\'impression CUPS est redémarré');
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
