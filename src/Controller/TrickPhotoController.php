<?php

namespace App\Controller;


use App\Entity\TrickPhoto;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TrickPhotoController extends AbstractController
{
    /**
     * @Route("delete-photo-trick/{id}", name="trick_photo_delete")
     */
    public function delete(Request $request, TrickPhoto $trickPhoto, Security $security): Response
    {
        $trick = $trickPhoto->getTrick();

        if (!$security->isGranted('ROLE_ADMIN')) {
            if (!$this->isGranted('DELETE', $trick)) {
                throw $this->createAccessDeniedException();
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trickPhoto);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}