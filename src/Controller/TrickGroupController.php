<?php


namespace App\Controller;


use App\Entity\TrickGroup;
use App\Form\TrickGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickGroupController extends AbstractController
{
    /**
     * @Route("add-group-trick", name="group_trick_add")
     * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("update-group-trick/{id}", name="group_trick_update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(Request $request, TrickGroup $trickGroup): Response
    {
        $form = $this->createForm(TrickGroupType::class, $trickGroup);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->render('trick-group/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete-group-trick/{id}", name="group_trick_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(TrickGroup $trickGroup): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trickGroup);
        $entityManager->flush();

        // Todo: Add a redirect route.
    }
}