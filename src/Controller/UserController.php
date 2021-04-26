<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder, $appSecret,
                           $ciphering, $iv, MailerInterface $mailer): Response
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

            $registrationToken = bin2hex(random_bytes(48)) . ':' . $user->getUsername() . ':' . time();

            $registrationToken = openssl_encrypt($registrationToken, $ciphering, $appSecret, $options=0, $iv);

            $user->setRegistrationToken($registrationToken);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Un lien vous a été envoyé mail pour confirmer votre compte.'
            );

            $email = (new Email())
                ->from('contact@briandidierjean.dev')
                ->to($user->getEmail())
                ->subject('Subject:Validation de votre compte')
                ->text(
                    'Cliquez sur ce lien pour valider votre compte: 
                    https://projet5-oc.briandidierjean.dev/validate-registration/'.base64_encode($user->getRegistrationToken())
                );
            $mailer->send($email);
        }

        return $this->render('user/sign-up.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("validate-registration/{registrationToken}", name="user_validate_registration")
     */
    public function validateRegistration(UserRepository $userRepository, $registrationToken, $ciphering, $appSecret, $iv): Response
    {
        $registrationToken = base64_decode($registrationToken);
        $registrationToken = openssl_decrypt($registrationToken, $ciphering, $appSecret, $options = 0, $iv);

        $registrationTokenList = explode(':', $registrationToken);
        $tokenUsername = $registrationTokenList[1];
        $tokenTime = $registrationTokenList[2];

        $user = $userRepository->findOneBy(["username" => $tokenUsername]);

        if ($user->getRegistrationToken() == $registrationToken && $tokenTime + 3600 * 48 > time()) {

            $user->setResetPasswordToken(null);
            $user->setStatus('validated');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte est validé.'
            );

            $this->redirectToRoute('user_sign_in');
        }
        throw $this->createNotFoundException('This token does not exist.');
    }

    /**
     * @Route("change-password", name="user_change_password")
     * @IsGranted("ROLE_USER")
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre mot de passe a bien été changé.'
            );
        }

        return $this->render('user/change-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("change-avatar", name="user_change_avatar")
     * @IsGranted("ROLE_USER")
     */
    public function changeAvatar()
    {
        return $this->render('user/change-avatar.html.twig', []);
    }

    /**
     * @Route("forget-password", name="user_forget_password")
     */
    public function forgetPassword(Request $request, UserRepository $userRepository, MailerInterface $mailer, $appSecret, $ciphering, $iv): Response
    {
        $defaultData = [];

        $form = $this->createFormBuilder($defaultData)
            ->add('username', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = $userRepository->findOneBy(["username" => $data['username']]);

            if ($user) {
                $resetPasswordToken = bin2hex(random_bytes(48)) . ':' . $user->getUsername() . ':' . time();

                $resetPasswordToken = openssl_encrypt($resetPasswordToken, $ciphering, $appSecret, $options=0, $iv);

                $user->setResetPasswordToken($resetPasswordToken);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                $email = (new Email())
                    ->from('contact@briandidierjean.dev')
                    ->to($user->getEmail())
                    ->subject('Subject:Réinitialisation de mot de passe')
                    ->text(
                        'Cliquez sur ce lien pour réinitialiser votre mot de passe:
                        https://projet5-oc.briandidierjean.dev/reset-password/'.base64_encode($user->getResetPasswordToken())
                    );
                $mailer->send($email);
            }

            $this->addFlash(
                'notice',
                'Si le pseudo est correct, un mail sera envoyé à l\'adresse associée.'
            );
        }

        return $this->render('user/forget-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("reset-password/{resetPasswordToken}", name="user_reset_password")
     */
    public function resetPassword(Request $request, UserRepository $userRepository, $resetPasswordToken, UserPasswordEncoderInterface $passwordEncoder, $appSecret, $ciphering, $iv): Response
    {
        $defaultData = [];

        $form = $this->createFormBuilder($defaultData)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'Mot de passe (confirmation) : '],
            ])
            ->add('resetPasswordToken', HiddenType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resetPasswordToken = base64_decode($resetPasswordToken);
            $resetPasswordToken = openssl_decrypt($resetPasswordToken, $ciphering, $appSecret, $options=0, $iv);

            $resetPasswordTokenList = explode(':', $resetPasswordToken);
            $tokenUsername = $resetPasswordTokenList[1];
            $tokenTime = $resetPasswordTokenList[2];

            $user = $userRepository->findOneBy(["username" => $tokenUsername]);

            if ($user->getResetPasswordToken() == $resetPasswordToken && $tokenTime + 3600 * 24 > time()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $user->setResetPasswordToken(null);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été changé.'
                );
            }
        }

        return $this->render('user/reset-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
