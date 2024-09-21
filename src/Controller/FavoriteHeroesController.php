<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FavoriteHeroesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteHeroesController extends AbstractController
{
    private RequestStack $requestStack;
    private UserRepository $userRepository;
    private FavoriteHeroesService $favoriteHeroesService;

    public function __construct(
        RequestStack $requestStack,
        UserRepository $userRepository,
        FavoriteHeroesService $favoriteHeroesService,
    ) {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
        $this->favoriteHeroesService = $favoriteHeroesService;
    }

    #[Route('/api/favorite/{favoriteId}/heroes', name: 'add_favorite_heroes', methods: ['POST'])]
    public function addHeroesToFavorite(int $favoriteId, Request $request): JsonResponse
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated.'], 401);
        }

        $data = json_decode($request->getContent(), true);
        $heroIds = $data['heroes'] ?? [];

        if (empty($heroIds)) {
            return new JsonResponse(['error' => 'Heroes list is empty'], 400);
        }

        try {
            $addedHeroes = $this->favoriteHeroesService->addHeroesToFavorite($user, $favoriteId, $heroIds);

            return new JsonResponse([
                'message' => 'Heroes added to favorite successfully',
                'added_heroes' => $addedHeroes,
            ], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/api/favorite/{favoriteId}/heroes/{heroId}', name: 'remove_favorite_hero', methods: ['DELETE'])]
    public function removeHeroFromFavorite(int $favoriteId, int $heroId): JsonResponse
    {
        $user = $this->getCurrentUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not authenticated.'], 401);
        }

        try {
            $this->favoriteHeroesService->removeHeroFromFavorite($user, $favoriteId, $heroId);

            return new JsonResponse(['message' => 'Hero removed from favorite successfully'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    private function getCurrentUser(): ?User
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');

        if (!$userId) {
            return null;
        }

        return $this->userRepository->find($userId);
    }
}
