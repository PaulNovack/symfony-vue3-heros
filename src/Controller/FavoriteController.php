<?php

namespace App\Controller;

// Make sure to import the correct namespace
use App\Service\FavoriteService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    private FavoriteService $favoriteService;
    private UserService $userService;

    public function __construct(FavoriteService $favoriteService,
        UserService $userService)
    {
        $this->favoriteService = $favoriteService;
        $this->userService = $userService;
    }

    #[Route('/api/favorite', name: 'create_favorite', methods: ['POST'])]
    public function createFavorite(Request $request, SessionInterface $session): JsonResponse
    {

        $user = $this->userService->getCurrentUser($request);
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        if (!$name) {
            return new JsonResponse(['error' => 'Invalid data. Name is required.'], 400);
        }

        $this->favoriteService->createFavorite($user, $name);

        return new JsonResponse(['message' => 'Favorite created successfully.'], 201);
    }

    #[Route('/api/favorite/{id}', name: 'delete_favorite', methods: ['DELETE'])]
    public function deleteFavorite(int $id, Request $request): JsonResponse
    {
        $user = $this->userService->getCurrentUser($request);

        $deleted = $this->favoriteService->deleteFavorite($user, $id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Favorite not found or you don\'t have permission.'], 404);
        }

        return new JsonResponse(['message' => 'Favorite deleted successfully.'], 200);
    }

    #[Route('/api/favorites', name: 'list_favorites', methods: ['GET'])]
    public function listFavorites(Request $request): JsonResponse
    {
        $user = $this->userService->getCurrentUser($request);

        $favoritesData = $this->favoriteService->listFavorites($user);

        return new JsonResponse($favoritesData, 200);
    }
}
