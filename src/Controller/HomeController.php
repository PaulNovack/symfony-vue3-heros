<?php

namespace App\Controller;

use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/', name: 'app_home')]
    #[Route('/heros', name: 'app_heros')]
    #[Route('/about', name: 'app_about')]
    #[Route('/marvel', name: 'app_marvel')]
    public function index(Request $request, SessionInterface $session): Response
    {
        $user = $this->userService->getCurrentUser($request);

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
