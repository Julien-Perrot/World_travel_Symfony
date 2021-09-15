<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/user")
 */
class AuthController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $passwordHashe = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($passwordHashe);

            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('auth/index.html.twig', [
            'formulaire' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */

     public function login()
     {
        return $this->render("auth/login.html.twig");
     }

     /**
      * @Route("/logout", name="logout")
      */
     public function logout()
     {

     }

}
