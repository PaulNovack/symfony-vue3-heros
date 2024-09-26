<?php

namespace App\Controller;

use App\Service\FavoriteHeroesService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteHeroesController extends AbstractController
{
    private UserService $userService;
    private FavoriteHeroesService $favoriteHeroesService;

    public function __construct(
        UserService $userService,
        FavoriteHeroesService $favoriteHeroesService,
    ) {
        $this->userService = $userService;
        $this->favoriteHeroesService = $favoriteHeroesService;
    }

    #[Route('/api/favorite/{favoriteId}/heroes', name: 'add_favorite_heroes', methods: ['POST'])]
    public function addHeroesToFavorite(int $favoriteId, Request $request, SessionInterface $session): JsonResponse
    {
        $user = $this->userService->getCurrentUser($request);
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
    public function removeHeroFromFavorite(int $favoriteId, int $heroId, Request $request): JsonResponse
    {
        $user = $this->userService->getCurrentUser($request);

        try {
            $this->favoriteHeroesService->removeHeroFromFavorite($user, $favoriteId, $heroId);

            return new JsonResponse(['message' => 'Hero removed from favorite successfully'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }
}
