<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{

    /**
     * @Route("/email/photo/order/{order_id}", name="admin_email_order")
     */
    public function emailPhotoOrderAction(Request $request, $order_id)
    {

        $config = $this->get('app_config')->getConfig();

        $photos = $this->getDoctrine()->getRepository('GalleryBundle:Photo')->getOrderPhotos($order_id);
        $order = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderUser($order_id);

        if(is_null($photos))
        {
            $request->getSession()->getFlashBag()->add('warning', "La commande n'a pas été trouvée");
            return $this->redirect($request->headers->get('referer'));
        }

        $message = \Swift_Message::newInstance()
            ->setSubject($config->getApplicationSellFilesEmailSubject())
            ->setFrom($config->getApplicationSellFilesEmailSender())
            ->setTo($order->getUser()->getEmail())
            ->setBody($config->getApplicationSellFilesEmailBody(), 'text/html')
            ->addPart(strip_tags($config->getApplicationSellFilesEmailBody()), 'text/plain');

        $appPath = $this->container->getParameter('kernel.root_dir');
        $webPath = realpath($appPath . '/../web');

        $helper = $this->get('vich_uploader.templating.helper.uploader_helper');

        foreach ($photos as $photo) {
            $imagePath = $helper->asset($photo, 'imageFile');
            $absoluteImagePath = $webPath . $imagePath;
            $message->attach(\Swift_Attachment::fromPath($absoluteImagePath));
        }

        $this->get('mailer')->send($message);

        $request->getSession()->getFlashBag()->add('success', 'Email envoyé avec ' . count($photos) . ' photos.');

        return $this->redirect($request->headers->get('referer'));
    }

}
