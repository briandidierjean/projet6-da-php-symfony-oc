<?php


namespace App\Controller;


use App\Entity\Message;
use App\Entity\Trick;
use App\Repository\MessageRepository;
use App\Repository\TrickPhotoRepository;
use App\Repository\TrickRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("load-more-messages", methods={"POST"}, name="message_load_more")
     */
    public function load(Request $request, MessageRepository $messageRepository): Response
    {
        $last = false;

        $offset = json_decode($request->get('offset'));
        if (isset($offset)) {
            $messages = $messageRepository->findBy([], ['creationDate' => 'DESC'], 10, $offset);

            $output = [];
            $lastMessage = $messageRepository->findOneBy([], ['creationDate' => 'ASC']);
            foreach ($messages as $message) {
                if ($lastMessage === $message) {
                    $last = true;
                }
                $output[] =  ['content' => $message->getContent(), 'username' => $message->getUser()->getUsername(), 'id' => $message->getId()];
            }

            dump($output);

            return new Response(json_encode(['output' => $output, 'last' => $last]));
        }
    }

    /**
     * @Route("add-message/{id}", name="message_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, Trick $trick): Response
    {
        $message = new Message();

        $messageContent = $request->request->get('message');

        if ($messageContent < 10 && $messageContent > 255) {
            //TODO: Exception
        }

        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $message->setUser($user);

        $message->setTrick($trick);

        $message->setContent($messageContent);

        dump($message);

        $entityManager->persist($message);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("delete-message/{id}", name="message_delete")
     */
    public function delete(Request $request, Message $message): Response
    {
        if (!$this->isGranted('DELETE', $message)) {
            throw $this->createAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}