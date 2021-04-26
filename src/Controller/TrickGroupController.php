<?php


namespace App\Controller;


use App\Entity\TrickGroup;
use App\Form\TrickGroupType;
use App\Repository\TrickGroupRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickGroupController extends AbstractController
{
    /**
     * @Route("groups", name="trick_group_index")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(TrickGroupRepository $trickGroupRepository): Response
    {
        $trickGroups = $trickGroupRepository->findAll();

        return $this->render('trick-group/index.html.twig', [
            'trickGroups' => $trickGroups
        ]);
    }

    /**
     * @Route("add-group-trick", name="trick_group_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(Request $request, TrickGroupRepository $trickGroupRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $trickGroup = new TrickGroup();

        $trickGroup->setName($request->request->get('name'));

        $entityManager->persist($trickGroup);
        $entityManager->flush();

        //TODO: Add redirect route
    }

    /**
     * @Route("update-group-trick/{id}", name="trick_group_update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(TrickGroup $trickGroup, Request $request): Response
    {
            $entityManager = $this->getDoctrine()->getManager();

            $trickGroup->setName($request->request->get('name'));

            $entityManager->flush();

        //TODO: Add redirect route
    }

    /**
     * @Route("delete-group-trick/{id}", name="trick_group_delete")
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