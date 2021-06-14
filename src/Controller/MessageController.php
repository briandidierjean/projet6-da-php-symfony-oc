<?php


namespace App\Controller;


use App\Entity\Message;
use App\Entity\Trick;
use App\Repository\MessageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MessageController extends AbstractController
{
    /**
     * @Route("load-more-messages", methods={"POST"}, name="message_load_more")
     */
    public function load(Request $request, MessageRepository $messageRepository): Response
    {
        $last = true;

        $offset = json_decode($request->get('offset'));
        if (isset($offset)) {
            $messages = $messageRepository->findBy([], ['creationDate' => 'DESC'], 10, $offset);

            if ($messages) {
                $last = false;
            }

            $output = [];
            $lastMessage = $messageRepository->findOneBy([], ['creationDate' => 'ASC']);
            foreach ($messages as $message) {
                if ($lastMessage === $message) {
                    $last = true;
                }
                $output[] =  ['content' => $message->getContent(), 'username' => $message->getUser()->getUsername(), 'id' => $message->getId(), 'creationDate' => $message->getCreationDate()->format("d/m/Y")];
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
    public function delete(Request $request, Message $message, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            if (!$this->isGranted('DELETE', $message)) {
                throw $this->createAccessDeniedException();
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($message);
        $entityManager->flush();

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}