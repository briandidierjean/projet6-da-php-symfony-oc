<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Entity\TrickPhoto;
use App\Form\TrickType;
use App\Repository\MessageRepository;
use App\Repository\TrickPhotoRepository;
use App\Repository\TrickRepository;
use App\Repository\TrickVideoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickController extends AbstractController
{
    /**
     * @Route("", name="trick_home")
     */
    public function index(TrickRepository $trickRepository): Response
    {
        $tricks = $trickRepository->findBy([], ['creationDate' => 'DESC'], 15, 0);

        return $this->render('trick/home.html.twig', [
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("load-more-tricks", methods={"POST"}, name="trick_load_more")
     */
    public function load(Request $request, TrickRepository $trickRepository, TrickPhotoRepository $trickPhotoRepository): Response
    {
        $last = false;

        $offset = json_decode($request->get('offset'));
        if (isset($offset)) {
            $tricks = $trickRepository->findBy([], ['creationDate' => 'DESC'], 15, $offset);

            $output = [];
            $lastTrick = $trickRepository->findOneBy([], ['creationDate' => 'ASC']);
            foreach ($tricks as $trick) {
                if ($lastTrick === $trick) {
                    $last = true;
                }
                $photos = $trickPhotoRepository->find($trick->getId());
                $output[] =  ['name' => $trick->getName(), 'photos' => $photos, 'id' => $trick->getId()];
            }

            return new Response(json_encode(['output' => $output, 'last' => $last]));
        }
    }

    /**
     * @Route("show-trick/{id}", name="trick_show")
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("add-trick", name="trick_add")
     * @IsGranted("ROLE_USER")
     */
    public function add(Request $request, SluggerInterface $slugger): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            $trick->setUser($user);

            $photoFiles = $form->get('photos')->getData();

            if ($photoFiles) {
                foreach ($photoFiles as $photoFile) {
                    $originalFilename = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $photoFile->guessExtension();

                    try {
                        $photoFile->move(
                            $this->getParameter('photos_directory'),
                            $newFilename
                        );

                        $trickPhoto = new TrickPhoto();
                        $trickPhoto->setName($newFilename);
                        $trickPhoto->setTrick($trick);

                        $entityManager->persist($trickPhoto);

                    } catch (FileException $e) {
                        //TODO : handle exception
                    }
                }
            }
            $entityManager->persist($trick);
            $entityManager->flush();
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("update-trick/{id}", name="trick_update")
     */
    //TODO: Mise à jour si on est administrateur
    public function update(Request $request, Trick $trick): Response
    {
        if (!$this->isGranted('UPDATE', $trick)) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->render('trick/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("delete-trick/{id}", name="trick_delete")
     */
    public function delete(Trick $trick): Response
    {
        if (!$this->isGranted('DELETE', $trick)) {
            throw $this->createAccessDeniedException();
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();

        // Todo: Add a redirect route.
    }
}