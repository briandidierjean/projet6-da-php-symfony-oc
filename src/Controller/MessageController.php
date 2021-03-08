<?php


namespace App\Controller;


use App\Entity\Message;
use App\Entity\Trick;
use App\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("add-message/{trickId}", name="message_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, $trickId): Response
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $this->getUser();
            $message->setUser($user);

            $repository = $this->getDoctrine()->getRepository(Trick::class);
            $trick = $repository->find($trickId);
            $message->setTrick($trick);

            dump($message);

            $entityManager->persist($message);
            $entityManager->flush();
        }


        return $this->render('message/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete-message/{id}", name="message_delete")
     */
    public function delete(Message $message): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        // Todo: Add a redirect route.
    }
}