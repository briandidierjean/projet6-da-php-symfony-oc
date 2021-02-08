<?php


namespace App\Controller;


use App\Entity\TrickGroup;
use App\Form\TrickGroupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickGroupController extends AbstractController
{
    /**
     * @Route("add-group-trick", name="group_trick_add")
     */
    public function add(Request $request): Response
    {
        $trickGroup = new TrickGroup();

        $form = $this->createForm(TrickGroupType::class, $trickGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trickGroup);
            $entityManager->flush();
        }

        return $this->render('trick-group/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}