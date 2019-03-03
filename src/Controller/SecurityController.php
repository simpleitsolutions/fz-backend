<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            ]
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/user/edit/{id}", name="security_user_edit")
     */
    public function userEditAction($id, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($id);

        if(empty($user))
        {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $passwordPlainText = $user->getPlainTextPassword();
            if(!empty($passwordPlainText))
            {
                $password = $passwordEncoder->encodePassword($user, $passwordPlainText);
                $user->setPassword($password);
                $this->addFlash('sonata_flash_success','Password has been updated!');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            $this->addFlash('sonata_flash_success','User '.$user->getUsername() .' has been updated!');
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/user/profile/edit", name="security_user_profile_edit")
     */
    public function userProfileEditAction(UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->remove('roles');
        $form->remove('pilot');
        $form->add('save', SubmitType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $passwrodPlainText = $user->getPlainTextPassword();
            if(!empty($passwrodPlainText))
            {
                $password = $passwordEncoder->encodePassword($user, $passwrodPlainText);
                $user->setPassword($password);
                $this->addFlash('sonata_flash_success','Password has been successfully changed!');
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            $this->addFlash('sonata_flash_success','Your user profile has been successfully updated!');
//            return $this->redirectToRoute('booking_custom_schedule');
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
        ]);

    }
}
