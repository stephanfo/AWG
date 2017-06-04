<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class OrderExportController extends Controller
{

    /**
     * @Route("/order/export", name="admin_order_export")
     */
    public function listAction()
    {
        $orderHeaders = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderArray();
        $orderDetails = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderDetailArray();
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->getAllUserArray();
        $galleries = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getGalleriesArray();
        $formats = $this->getDoctrine()->getRepository('CartBundle:Format')->getFormatArray();
        $prices = $this->getDoctrine()->getRepository('CartBundle:Price')->getPricesArray();

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Stéphane RATELET (Life in Pix)")
            ->setLastModifiedBy("Stéphane RATELET (Life in Pix)")
            ->setTitle("AWG information")
            ->setSubject("All details from AWG application")
            ->setDescription("This file compiles all information included in the AWG application.")
            ->setKeywords("awg data")
            ->setCategory("AWG");

        $sheet = $phpExcelObject->setActiveSheetIndex(0)->setTitle('Order headers');

        if(!is_null($orderHeaders))
        {
            $sheet->fromArray(array_keys($orderHeaders[0]), NULL, 'A1');
            $sheet->fromArray($orderHeaders, NULL, 'A2');
        }

        $sheet = $phpExcelObject->createSheet()->setTitle('Order details');

        if(!is_null($orderDetails))
        {
            $sheet->fromArray(array_keys($orderDetails[0]), NULL, 'A1');
            $sheet->fromArray($orderDetails, NULL, 'A2');
        }

        $sheet = $phpExcelObject->createSheet()->setTitle('Users list');

        if(!is_null($users))
        {
            $sheet->fromArray(array_keys($users[0]), NULL, 'A1');
            $sheet->fromArray($users, NULL, 'A2');
        }

        $sheet = $phpExcelObject->createSheet()->setTitle('Galleries Photos list');

        if(!is_null($galleries))
        {
            $sheet->fromArray(array_keys($galleries[0]), NULL, 'A1');
            $sheet->fromArray($galleries, NULL, 'A2');
        }

        $sheet = $phpExcelObject->createSheet()->setTitle('Format list');

        if(!is_null($formats))
        {
            $sheet->fromArray(array_keys($formats[0]), NULL, 'A1');
            $sheet->fromArray($formats, NULL, 'A2');
        }

        $sheet = $phpExcelObject->createSheet()->setTitle('Price list');

        if(!is_null($prices))
        {
            $sheet->fromArray(array_keys($prices[0]), NULL, 'A1');
            $sheet->fromArray($prices, NULL, 'A2');
        }

        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'AWGContent_' . date('Y-m-d', strtotime("now")) . '.xlsx'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
