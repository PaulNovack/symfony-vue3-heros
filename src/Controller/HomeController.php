<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    #[Route('/heros', name: 'app_heros')]
    #[Route('/about', name: 'app_about')]
    #[Route('/marvel', name: 'app_marvel')]
    public function index(Request $request, SessionInterface $session): Response
    {


        if (!$session->has('user_id')) {
            $ipAddress = $request->getClientIp();
            $user = new User();
            $user->setIpAddress($ipAddress);
            $user->setSessionId($session->getId());
            $user->setName('Guest '.uniqid());
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $session->set('user_id', $user->getId());
        }

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
