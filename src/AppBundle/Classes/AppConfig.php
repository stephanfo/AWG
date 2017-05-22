<?php

namespace AppBundle\Classes;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Config;

class AppConfig
{

    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getConfig()
    {
        $config = $this->em->getRepository('AppBundle:Config')->findOneBy(array());

        if (is_null($config))
            $config = $this->loadDefaultConfig();

        return $config;
    }

    public function loadDefaultConfig()
    {
        $config = new Config();

        // Default configuration of the App
        $config->setApplicationTheme(null);
        $config->setApplicationIntroMessage("<p style=\"text-align: center;\">&nbsp;</p>
<hr />
<p style=\"text-align: center;\"><strong>Acc&egrave;s &agrave; la galerie</strong></p>
<hr />
<p>&nbsp;</p>
<p style=\"text-align: center;\">Pour vous connecter &agrave; la galerie &agrave; partir de votre smartphone:</p>
<p>&nbsp;</p>
<p style=\"text-align: center;\">Connecter vous au r&eacute;seau wifi : <span style=\"text-decoration: underline;\"><strong>Blabla</strong></span>.</p>
<p style=\"text-align: center;\">Saisissez l'adresse <span style=\"text-decoration: underline;\"><strong>blabla/</strong></span>&nbsp;ou <span style=\"text-decoration: underline;\"><strong>http://blabla</strong></span>&nbsp;dans votre navigateur.</p>");
        $config->setApplicationSellFiles(false);
        $config->setApplicationSellFilesAllowDownload(false);
        $config->setApplicationSellFilesForceDownload(false);
        $config->setApplicationSellFilesEmailSubject("Vos photos Life in PIX");
        $config->setApplicationSellFilesEmailBody("<p>Bonjour</p>
<p>Je vous remercie pour votre commande de photos. Vous trouverez vos fichiers&nbsp;dans les pi&egrave;ces jointes de cet email.</p>
<p>Cordialement.</p>");
        $config->setApplicationSellFilesEmailSender("your.email@domain.com");
        $config->setApplicationSellFilesEmailSenderInCc(true);
        $config->setGalleryQuickLink(true);
        $config->setGallerySingleGallery(false);

        $this->em->persist($config);
        $this->em->flush();

        return $config;
    }
}