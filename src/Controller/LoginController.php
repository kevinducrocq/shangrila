<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // Erreur s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier "username" entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('users/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
