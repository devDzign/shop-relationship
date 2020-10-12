<?php

namespace App\Controller;

use App\Dto\ForgottenPasswordInput;
use App\Entity\Customer;
use App\Entity\Producer;
use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\Uuid;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{role}/sign-up ", name="app.sign-up", requirements={"role": "producer|customer"})
     * @param Request                      $request
     * @param string                       $role
     *
     * @param EntityManagerInterface       $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return Response
     */
    public function registration(
        Request $request,
        string $role,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        $user = Producer::ROLE === $role ? new Producer() : new Customer();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPlainPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash("success", "votre inscription a été effectuée avec succés");

            return $this->redirectToRoute("welcome_dev");
        }

        return $this->render('ui/security/registration.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render("ui/security/login.html.twig", [
            "last_username" => $authenticationUtils->getLastUsername(),
            "error" => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * @codeCoverageIgnore
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param MailerInterface $mailer
     * @return Response
     * @Route("/forgotten-password", name="security_forgotten_password")
     */
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer
    ): Response {
        $forgottenPasswordInput = new ForgottenPasswordInput();

        $form = $this->createForm(ForgottenPasswordType::class, $forgottenPasswordInput)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $userRepository->findOneByEmail($forgottenPasswordInput->getEmail());
            $user->hasForgotHisPassword();
            $this->getDoctrine()->getManager()->flush();


            $email = (new TemplatedEmail())
                ->to(new Address($user->getEmail(), $user->getFullName()))
                ->from("hello@producteurauconsommateur.com")
                ->context(["forgottenPassword" => $user->getForgottenPassword()])
                ->htmlTemplate('emails/forgotten_password.html.twig');

            $mailer->send($email);
            $this->addFlash(
                "success",
                "Votre demande d'oubli de mot de passe a bien été enregistrée. 
                Vous allez recevoir un email pour réinitialiser votre mot de passe"
            );
            return $this->redirectToRoute("security_login");
        }

        return $this->render("ui/security/forgotten_password.html.twig", [
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/reset-password/{token}", name="security_reset_password")
     * @param string $token
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {

        $user = $userRepository->getUserByForgottenPasswordToken(Uuid::fromString($token));

        if (null === $user) {
            $this->addFlash("danger", "Cette demande d'oubli de mot de passe n'existe pas.");
        }

        $form = $this->createForm(ResetPasswordType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPlainPassword())
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                "success",
                "Votre mot de passe a été modifié avec succès."
            );
            return $this->redirectToRoute("security_login");
        }

        return $this->render("ui/security/reset_password.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
