<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use GalleryBundle\Entity\Photo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class LikeController extends Controller
{

    /**
     * @Route("/ajax/like/toggle/{id}", requirements={"id": "\d*"}, name="like_toggle")
     * @Route("/ajax/like/toggle/", name="like_toggle_empty_link")
     * @Method({"GET"})
     */
    public function toogleLikeAction(Photo $photo)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if (!$photo->getLikeUsers()->contains($user))
        {
            $photo->addLikeUser($user);
            $photo->increaseLikeCount();
            $em->flush();
            return $this->json(array(
                        "id" => $photo->getId(),
                        "like" => true,
                        "count" => $photo->getLikeCount()
            ));
        }
        else
        {
            $photo->removeLikeUser($user);
            $photo->decreaseLikeCount();
            $em->flush();
            return $this->json(array(
                        "id" => $photo->getId(),
                        "like" => false,
                        "count" => $photo->getLikeCount()
            ));
        }
    }
}
