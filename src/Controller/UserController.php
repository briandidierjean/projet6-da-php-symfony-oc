<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("sign-in", name="user_sign_in")
     */
    public function signIn(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/sign-in.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("sign-out", name="user_sign_out")
     */
    public function signOut(): void
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("sign-up", name="user_sign_up")
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main'
            );
        }

        return $this->render('user/sign-up.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("change-password", name="user_change_password")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager =$this->getDoctrine()->getManager();
            $entityManager->flush();
        }

        return $this->render('user/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("forget-password", name="user_forget_password")
     */
    public function forgetPassword(Request $request, MailerInterface $mailer): Response
    {
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('email', EmailType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneBy(["email" => $data['email']]);

            $user->setResetPasswordToken(bin2hex(random_bytes(78)).'/date:'.time());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            dump($user);

            $email = (new Email())
                ->from('snowtricks@briandidierjean.dev')
                ->to($user->getEmail())
                ->subject('Réinitialisation de mot de passe')
                ->text('TEST 122738173813918');
            $mailer->send($email);
        }

        return $this->render('user/forget-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
