<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private RequestStack $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    public function getCurrentUser(Request $request): ?User
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');

        if ($userId) {
            return $this->userRepository->find($userId);
        }

        return $this->createGuestUser($request);
    }

    private function createGuestUser(Request $request): User
    {
        $session = $this->requestStack->getSession();
        $ipAddress = $request->getClientIp();
        $user = new User();
        $user->setIpAddress($ipAddress);
        $user->setSessionId($session->getId());
        $user->setName('Guest '.uniqid());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $session->set('user_id', $user->getId());

        return $user;
    }
}
