<?php

namespace CartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class OrderExportController extends Controller
{

    /**
     * @Route("/order/export", name="admin_order_export")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $excelSheets = array();

        $excelSheets["Order headers"] = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderArray();
        $excelSheets["Order details"] = $this->getDoctrine()->getRepository('CartBundle:Order')->getOrderDetailArray();
        $excelSheets["Users list"] = $this->getDoctrine()->getRepository('UserBundle:User')->getAllUserArray();
        $excelSheets["Galleries Photos list"] = $this->getDoctrine()->getRepository('GalleryBundle:Gallery')->getGalleriesArray();
        $excelSheets["Format list"] = $this->getDoctrine()->getRepository('CartBundle:Format')->getFormatArray();
        $excelSheets["Price list"] = $this->getDoctrine()->getRepository('CartBundle:Price')->getPricesArray();

        return $this->generateExcelFile($excelSheets);

    }

    private function generateExcelFile($excelSheets)
    {
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("Stéphane RATELET (Life in Pix)")
            ->setLastModifiedBy("Stéphane RATELET (Life in Pix)")
            ->setTitle("AWG information")
            ->setSubject("All details from AWG application")
            ->setDescription("This file compiles all information included in the AWG application.")
            ->setKeywords("awg data")
            ->setCategory("AWG");

        $sheet = $phpExcelObject->setActiveSheetIndex(0)->setTitle('Resume');
        $sheet->setCellValue('A1', "Please select on of the sheets below to get the details");

        foreach ($excelSheets as $sheetName => $excelSheet)
        {
            $sheet = $phpExcelObject->createSheet()->setTitle($sheetName);

            if(count($excelSheet) > 0)
            {
                $sheet->fromArray(array_keys($excelSheet[0]), NULL, 'A1');
                $sheet->fromArray($excelSheet, NULL, 'A2');
            }
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
