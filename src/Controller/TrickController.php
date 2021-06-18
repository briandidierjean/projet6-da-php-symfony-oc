<?php


namespace App\Controller;


use App\Entity\Trick;
use App\Entity\TrickPhoto;
use App\Entity\TrickVideo;
use App\Form\TrickType;
use App\Repository\TrickPhotoRepository;
use App\Repository\TrickRepository;
use App\Service\FilenameGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
        $last = true;

        $offset = json_decode($request->get('offset'));
        if (isset($offset)) {
            $tricks = $trickRepository->findBy([], ['creationDate' => 'DESC'], 15, $offset);

            if ($tricks) {
                $last = false;
            }

            $output = [];
            $lastTrick = $trickRepository->findOneBy([], ['creationDate' => 'ASC']);
            foreach ($tricks as $trick) {
                if ($lastTrick === $trick) {
                    $last = true;
                }
                $photos = $trickPhotoRepository->findBy(['trick' => $trick->getId()]);
                $photosNames = [];
                if ($photos !== null) {
                    foreach ($photos as $photo) {
                        $photosNames[] = ['name' => $photo->getName()];
                    }
                }
                $output[] = ['name' => $trick->getName(), 'slug' => $trick->getSlug(), 'mainPhoto' => $trick->getMainPhoto(), 'photos' => $photosNames, 'id' => $trick->getId()];
            }
            return new Response(json_encode(['output' => $output, 'last' => $last]));
        }
    }

    /**
     * @Route("show-trick/{slug}", name="trick_show")
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
    public function add(Request $request, FilenameGenerator $filenameGenerator): Response
    {
        $trick = new Trick();

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $slugger = new AsciiSlugger();
            $trick->setSlug($slugger->slug($form->get('name')->getData()));

            $user = $this->getUser();

            $trick->setUser($user);

            $mainPhotoFile = $form->get('mainPhoto')->getData();
            if ($mainPhotoFile) {
                if (!$filenameGenerator->checkPhotoExt($mainPhotoFile)) {
                    return $this->render('trick/add.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                $newFilename = $filenameGenerator->generate($mainPhotoFile);
                $trick->setMainPhoto($newFilename);
            }

            $photoFiles = $form->get('photos')->getData();
            if ($photoFiles) {
                foreach ($photoFiles as $photoFile) {

                    if (!$filenameGenerator->checkPhotoExt($photoFile)) {
                        return $this->render('trick/add.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }

                    $newFilename = $filenameGenerator->generate($photoFile);

                    $trickPhoto = new TrickPhoto();
                    $trickPhoto->setName($newFilename);
                    $trickPhoto->setTrick($trick);

                    $entityManager->persist($trickPhoto);
                }
            }

            $videos = $form->get('videos')->getData();

            if ($videos) {
                foreach ($videos as $video) {

                    if ($video->getName() !== null) {

                        $trickVideo = new TrickVideo();
                        $trickVideo->setName($video->getName());
                        $trickVideo->setTrick($trick);

                        $entityManager->persist($trickVideo);
                    }
                }
            }

            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_home');
        }

        return $this->render('trick/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("update-trick/{slug}", name="trick_update")
     */
    public function update(Request $request, Trick $trick, Security $security, FilenameGenerator $filenameGenerator): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            if (!$this->isGranted('UPDATE', $trick)) {
                throw $this->createAccessDeniedException();
            }
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $mainPhotoFile = $form->get('mainPhoto')->getData();
            if ($mainPhotoFile) {
                if (!$filenameGenerator->checkPhotoExt($mainPhotoFile)) {
                    return $this->render('trick/add.html.twig', [
                        'form' => $form->createView(),
                    ]);
                }
                $newFilename = $filenameGenerator->generate($mainPhotoFile);
                $trick->setMainPhoto($newFilename);
            }

            $photoFiles = $form->get('photos')->getData();
            if ($photoFiles) {
                foreach ($photoFiles as $photoFile) {

                    if (!$filenameGenerator->checkPhotoExt($photoFile)) {
                        return $this->render('trick/add.html.twig', [
                            'form' => $form->createView(),
                        ]);
                    }

                    $newFilename = $filenameGenerator->generate($photoFile);

                    $trickPhoto = new TrickPhoto();
                    $trickPhoto->setName($newFilename);
                    $trickPhoto->setTrick($trick);

                    $entityManager->persist($trickPhoto);
                }
            }

            $videos = $form->get('videos')->getData();

            dump($form);
            if ($videos) {
                foreach ($videos as $video) {

                    if ($video->getName() !== null) {

                        $trickVideo = new TrickVideo();
                        $trickVideo->setName($video->getName());
                        $trickVideo->setTrick($trick);

                        $entityManager->persist($trickVideo);
                    }
                }
            }

            $trick->setUpdateDate(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('trick_home');
        }

        return $this->render('trick/update.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("delete-trick/{slug}", name="trick_delete")
     */
    public function delete(Request $request, Trick $trick, Security $security): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            if (!$this->isGranted('DELETE', $trick)) {
                throw $this->createAccessDeniedException();
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($trick);
        $entityManager->flush();

        return $this->redirectToRoute('trick_home');
    }
}