<?php

namespace UserBundle\Classes;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class UserProfile
{

    protected $em;
    protected $session;

    public function __construct(EntityManager $entityManager, RequestStack $requestStack)
    {
        $this->em = $entityManager;
        $this->session = $requestStack->getCurrentRequest()->getSession();
    }

    public function validUser()
    {
        $user = $this->em->getRepository('UserBundle:User')->findOneBySession($this->session->getId());

        if (is_null($user))
            return false;

        return true;
    }

    public function getUser()
    {
        $user = $this->em->getRepository('UserBundle:User')->findOneBySession($this->session->getId());

        if (is_null($user))
            return null;

        return $user;
    }

    public function getUserId()
    {
        $user = $this->em->getRepository('UserBundle:User')->findOneBySession($this->session->getId());

        if (is_null($user))
            return null;

        return $user->getID();
    }

    public function getUserSession()
    {
        return $this->session->getId();
    }
}

?>

