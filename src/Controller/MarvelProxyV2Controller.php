<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MarvelApiV2Service;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\ItemInterface;

class MarvelProxyV2Controller extends AbstractController
{
    private MarvelApiV2Service $marvelApiV2Service;

    private int $cacheDuration;
    private EntityManagerInterface $entityManager;

    public function __construct(
        MarvelApiV2Service $marvelApiV2Service,
        int $cacheDuration,
        EntityManagerInterface $entityManager,
    ) {
        $this->marvelApiV2Service = $marvelApiV2Service;
        $this->cacheDuration = $cacheDuration;
        $this->entityManager = $entityManager;
    }

    #[Route('/apiv2/marvel/characters', name: 'marvel_proxy', methods: ['GET'])]
    public function charactersByName(Request $request, SessionInterface $session): JsonResponse
    {
        $ipAddress = $request->getClientIp();

        // Ensure user is stored in the session
        if (!$session->has('user_id')) {
            $user = new User();
            $user->setIpAddress($ipAddress);
            $user->setSessionId($session->getId());
            $user->setName('Guest '.uniqid());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        try {
            $queryParams = $request->query->all();
            $queryParams['limit'] ??= 10; // Default limit if not provided
            $limit = (int) $queryParams['limit']; // Convert to integer
            $nameStartsWith = $queryParams['nameStartsWith'] ?? '';

            $cacheKey = md5($nameStartsWith.json_encode($queryParams));
            $cache = new FilesystemAdapter();
            $cache->clear(); // Clears all entries from the cache
            // Use cache for API results
            $data = $cache->get($cacheKey, function (ItemInterface $item) use ($nameStartsWith, $limit) {
                $item->expiresAfter($this->cacheDuration);

                return $this->marvelApiV2Service->mergeCharactersByNameStartsWith($nameStartsWith, $limit);
            });

            return $this->json($data); // Symfony handles the JSON encoding
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/apiv2/marvel/charactersById/{ids}', name: 'characters_by_id', methods: ['GET'])]
    public function charactersById(string $ids, Request $request): JsonResponse
    {
        try {
            $characterIds = array_map('trim', explode(',', $ids));

            if ('' === $characterIds[0]) {
                return $this->json(['error' => 'Comma-separated character IDs are required'], 400);
            }

            $limit = (int) ($request->query->get('limit') ?? count($characterIds)); // Default to number of IDs if no limit provided

            $cacheKey = md5('charactersById-'.$ids);
            $cache = new FilesystemAdapter();

            // Use cache for character data by ID
            $data = $cache->get($cacheKey, function (ItemInterface $item) use ($characterIds, $limit) {
                $item->expiresAfter($this->cacheDuration);

                return $this->marvelApiV2Service->mergeCharacterJsonData($characterIds, $limit);
            });

            return $this->json($data); // Symfony handles the JSON encoding
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
