<?php

namespace App\Controller;


use App\Entity\TrickVideo;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class TrickVideoController extends AbstractController
{
    /**
     * @Route("delete-video-trick/{id}", name="trick_video_delete")
     */
    public function delete(Request $request, TrickVideo $trickVideo, Security $security): Response
    {
        $trick = $trickVideo->getTrick();

        if (!$security->isGranted('ROLE_ADMIN')) {
            if (!$this->isGranted('DELETE', $trick)) {
                throw $this->createAccessDeniedException();
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trickVideo);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}