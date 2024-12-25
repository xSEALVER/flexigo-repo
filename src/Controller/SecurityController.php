<?php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// class SecurityController extends AbstractController
// {
//     #[Route(path: '/login', name: 'app_login')]
//     public function login(AuthenticationUtils $authenticationUtils): Response
//     {
//         // if ($this->getUser()) {
//         //     return $this->redirectToRoute('target_path');
//         // }

//         // get the login error if there is one
//         $error = $authenticationUtils->getLastAuthenticationError();
//         // last username entered by the user
//         $lastUsername = $authenticationUtils->getLastUsername();

//         return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
//     }

//     #[Route(path: '/logout', name: 'app_logout')]
//     public function logout(): void
//     {
//         throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
//     }
// }

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// class SecurityController extends AbstractController
// {
//     #[Route(path: '/login', name: 'app_login')]
//     public function login(AuthenticationUtils $authenticationUtils): Response
//     {
//         // Vérifier si l'utilisateur est déjà connecté
//         if ($this->getUser()) {
//             if ($this->isGranted('ROLE_ADMIN')) {
//                 // Redirection pour les administrateurs
//                 return $this->redirectToRoute('app_cinema_index');
//             } else {
//                 // Redirection pour les utilisateurs standards
//                 return $this->redirectToRoute('app_movie'); // Modifiez selon votre route pour les utilisateurs
//             }
//         }

//         // Récupérer les erreurs de connexion
//         $error = $authenticationUtils->getLastAuthenticationError();
//         // Dernier identifiant saisi
//         $lastUsername = $authenticationUtils->getLastUsername();

//         return $this->render('security/login.html.twig', [
//             'last_username' => $lastUsername,
//             'error' => $error,
//         ]);
//     }

//     #[Route(path: '/logout', name: 'app_logout')]
//     public function logout(): void
//     {
//         throw new \LogicException('Cette méthode peut rester vide - elle sera interceptée par la clé logout du firewall.');
//     }
// }

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

// class SecurityController extends AbstractController
// {
//     #[Route(path: '/login', name: 'app_login')]
//     public function login(AuthenticationUtils $authenticationUtils): Response
//     {
        
        
//         // Redirect if user is already authenticated
//         if ($this->getUser()) {
//             if ($this->isGranted('ROLE_ADMIN')) {
//                 return $this->redirectToRoute('app_cinema_index'); // Admin route
//             } else {
//                 return $this->redirectToRoute('app_movie'); // Standard user route
//             }
//         }

        

//         // Fetch login error and last entered username
//         $error = $authenticationUtils->getLastAuthenticationError();
//         $lastUsername = $authenticationUtils->getLastUsername();

//         return $this->render('security/login.html.twig', [
//             'last_username' => $lastUsername,
//             'error' => $error,
//         ]);
//     }

//     #[Route(path: '/logout', name: 'app_logout')]
//     public function logout(): void
//     {
//         throw new \LogicException('This method can remain blank - it is intercepted by the logout key in the firewall.');
//     }
// }


// src/Controller/SecurityController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // If the user is logged in, redirect them based on their role
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                // Redirect to the admin dashboard
                return $this->redirectToRoute('admin');
            } else {
                // Redirect to a user page (modify accordingly)
                return $this->redirectToRoute('app_movie');
            }
        }

        // Get login error and last username
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}




