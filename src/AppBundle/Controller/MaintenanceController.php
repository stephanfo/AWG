<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MaintenanceController extends Controller {

    /**
     * @Route("/maintenance", name="app_maintenance")
     */
    public function maintenanceAction(Request $request)
    {
        $currentDate = new \DateTime('now');
        
        $formDate = $this->createFormBuilder()
            ->add('date', DateTimeType::class, array(
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy HH:mm:ss',
                'placeholder' => 'Permanent',
                'data' => $currentDate,
            ))
            ->getForm()
            ;

        $formDate->handleRequest($request);
        
        if($formDate->isSubmitted() && $formDate->isValid())
        {
            $resultArray = $formDate->getData();
            $newDate = $resultArray['date'];

            $command = "/usr/bin/sudo /bin/date -u -s \"@" . $newDate->getTimestamp() . "\"";

            $printProcess = new Process($command);
            $printProcess->run();

            if ($printProcess->isSuccessful())
            {
                $request->getSession()->getFlashBag()->add('success', 'L\horloge système a été mise à jour.');
            }
            else
            {
                $request->getSession()->getFlashBag()->add('danger', 'La commande : ' . $command . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
            }
        }
        
        return $this->render('AppBundle:Maintenance:maintenance.html.twig', array (
            'currentDate' => $currentDate,
            'formDate' => $formDate-> createView(),
        ));
    }

    /**
     * @Route("/maintenance/halt", name="app_maintenance_halt")
     */
    public function haltAction(Request $request)
    {
        $command = "/usr/bin/sudo /sbin/halt";

        $printProcess = new Process($command);
        $printProcess->run();

        if ($printProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('success', 'Le système va s\'arrêter.');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('danger', 'La commande : ' . $command . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
        }

        return $this->redirectToRoute('app_maintenance');
    }

    /**
     * @Route("/maintenance/reboot", name="app_maintenance_reboot")
     */
    public function rebootAction(Request $request)
    {
        $command = "/usr/bin/sudo /sbin/reboot";

        $printProcess = new Process($command);
        $printProcess->run();

        if ($printProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('success', 'Le système va redémarrer.');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('danger', 'La commande : ' . $command . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
        }

        return $this->redirectToRoute('app_maintenance');
    }

    /**
     * @Route("/maintenance/thumbs/clear", name="app_maintenance_thumbs_clear")
     */
    public function thumbsClearAction(Request $request)
    {
        $appPath = $this->container->getParameter('kernel.root_dir');
        $binPath = realpath($appPath . '/../bin');

        $command = "/usr/bin/php " . $binPath . "/console liip:imagine:cache:remove";


        $printProcess = new Process($command);
        $printProcess->run();

        if ($printProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('success', 'Les miniatures ont été supprimées.');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('danger', 'La commande : ' . $command . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
        }

        return $this->redirectToRoute('app_maintenance');
    }
    
    /**
     * @Route("/maintenance/cache/clear", name="app_maintenance_cache_clear")
     */
    public function cacheClearRestartAction(Request $request)
    {
        $appPath = $this->container->getParameter('kernel.root_dir');
        $binPath = realpath($appPath . '/../bin');

        $command = "/usr/bin/php " . $binPath . "/console cache:clear --env=prod && /usr/bin/php " . $binPath . "/console cache:clear --env=dev";


        $printProcess = new Process($command);
        $printProcess->run();

        if ($printProcess->isSuccessful()) {
            $request->getSession()->getFlashBag()->add('success', 'Le cache (dev & prod) de l\'application a été supprimé.');
        }
        else
        {
            $request->getSession()->getFlashBag()->add('danger', 'La commande : ' . $command . ' a échouée (message d\'erreur : '. $printProcess->getErrorOutput() . ').');
        }

        return $this->redirectToRoute('app_maintenance');
    }

    /**
     * @Route("/maintenance/app/reset", name="app_maintenance_reset_app")
     */
    public function appResetRestartAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $em->getFilters()->disable('softdeleteable');

        $em->createQuery('DELETE FROM CartBundle\Entity\CartQuantity')->execute();
        $em->createQuery('DELETE FROM CartBundle\Entity\Cart')->execute();
        $em->createQuery('DELETE FROM CartBundle\Entity\OrderQuantity')->execute();
        $em->createQuery('DELETE FROM CartBundle\Entity\Detail')->execute();

        $photos = $em->getRepository('GalleryBundle:Photo')->findAll();
        foreach ($photos as $photo)
        {
            $em->remove($photo);
        }
        $em->flush();

        $photos = $em->getRepository('GalleryBundle:Photo')->findAll();
        foreach ($photos as $photo)
        {
            $em->remove($photo);
        }
        $em->flush();
        
        $em->createQuery('DELETE FROM GalleryBundle\Entity\Gallery')->execute();
        $em->createQuery('DELETE FROM CartBundle\Entity\Order')->execute();
        $em->createQuery("DELETE FROM UserBundle\Entity\User u WHERE u.roles ='a:0:{}'")->execute();

        $request->getSession()->getFlashBag()->add('success', 'L\'application a été remise à zéro.');

        return $this->redirectToRoute('app_maintenance');
    }
    
}
