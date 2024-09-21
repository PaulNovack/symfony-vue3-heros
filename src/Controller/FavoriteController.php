<?php

namespace App\Controller;

use App\Entity\User; // Make sure to import the correct namespace
use App\Service\FavoriteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController
{
    private $favoriteService;
    private $requestStack;

    public function __construct(FavoriteService $favoriteService, RequestStack $requestStack)
    {
        $this->favoriteService = $favoriteService;
        $this->requestStack = $requestStack;
    }

    #[Route('/api/favorite', name: 'create_favorite', methods: ['POST'])]
    public function createFavorite(Request $request): JsonResponse
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not found in session.'], 404);
        }

        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;

        if (!$name) {
            return new JsonResponse(['error' => 'Invalid data. Name is required.'], 400);
        }

        $this->favoriteService->createFavorite($user, $name);

        return new JsonResponse(['message' => 'Favorite created successfully.'], 201);
    }

    #[Route('/api/favorite/{id}', name: 'delete_favorite', methods: ['DELETE'])]
    public function deleteFavorite(int $id): JsonResponse
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not found in session.'], 404);
        }

        $deleted = $this->favoriteService->deleteFavorite($user, $id);

        if (!$deleted) {
            return new JsonResponse(['error' => 'Favorite not found or you don\'t have permission.'], 404);
        }

        return new JsonResponse(['message' => 'Favorite deleted successfully.'], 200);
    }

    #[Route('/api/favorites', name: 'list_favorites', methods: ['GET'])]
    public function listFavorites(): JsonResponse
    {
        $user = $this->getCurrentUser();

        if (!$user) {
            return new JsonResponse(['error' => 'User not found in session.'], 404);
        }

        $favoritesData = $this->favoriteService->listFavorites($user);

        return new JsonResponse($favoritesData, 200);
    }

    private function getCurrentUser(): ?User
    {
        $session = $this->requestStack->getSession();
        $userId = $session->get('user_id');

        return $this->favoriteService->getCurrentUser($userId);
    }
}
