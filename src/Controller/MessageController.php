<?php


namespace App\Controller;


use App\Entity\Message;
use App\Form\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("add-message/{trickId}", name="message_add")
     * @IsGranted("ROLE_USER")
     */
    public function add()
    {
        $message = new Message();

        $form = $this->createForm(MessageType::class, $message);

        return $this->render('message/add.html.twig', []);
    }

    /**
     * @Route("update-message/{id}", name="message_update")
     */
    public function update()
    {
        return $this->render('message/update.html.twig', []);
    }

    /**
     * @Route("delete-message/{id}", name="message_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Message $message)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        // Todo: Add a redirect route.
    }
}