<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MarvelApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class MarvelProxyController extends AbstractController
{
    private $marvelApiService;
    private $cacheDuration;
    private EntityManagerInterface $entityManager;

    public function __construct(MarvelApiService $marvelApiService, int $cacheDuration, EntityManagerInterface $entityManager)
    {
        $this->marvelApiService = $marvelApiService;
        $this->cacheDuration = $cacheDuration;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/marvel/{endpoint}', name: 'marvel_proxy', methods: ['GET'])]
    public function proxyMarvelApi(string $endpoint, Request $request, SessionInterface $session): JsonResponse
    {
        // Get the client's IP address
        $ipAddress = $request->getClientIp();

        // Check if the session exists and if a user is already associated
        if (!$session->has('user_id')) {
            // No user found in the session, so we create a new User
            $user = new User();
            $user->setIpAddress($ipAddress);
            $user->setSessionId($session->getId());
            $user->setName('Guest '.uniqid()); // Setting default name as 'Guest' + unique ID

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        try {
            $queryParams = $request->query->all();
            if ('characters' == $endpoint) {
                $queryParams['limit'] ??= 10;
            }
            $cacheKey = md5($endpoint.json_encode($queryParams));
            $cache = new FilesystemAdapter();
            $data = $cache->get($cacheKey, function (ItemInterface $item) use ($endpoint, $queryParams) {
                $item->expiresAfter($this->cacheDuration);

                return $this->marvelApiService->fetchMarvelData($endpoint, $queryParams);
            });

            return $this->json($data);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
