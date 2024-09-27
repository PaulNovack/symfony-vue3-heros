<?php

namespace App\Service;

use App\Entity\Favorite;
use App\Entity\FavoriteHeroes;
use App\Entity\User;  // Ensure this is the correct namespace
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteService
{
    private $entityManager;
    private $userRepository;
    private $favoriteRepository;

    public function __construct(EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        FavoriteRepository $favoriteRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->favoriteRepository = $favoriteRepository;
    }

    public function createFavorite(User $user, string $name): Favorite
    {

        $favorite = new Favorite();
        $favorite->setName($name);
        $favorite->setUser($user);
        $favorite->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();

        return $favorite;
    }

    public function deleteFavorite(User $user, int $id): bool
    {
        $favorite = $this->favoriteRepository->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);

        if (!$favorite) {
            return false;
        }

        $this->entityManager->remove($favorite);
        $this->entityManager->flush();

        return true;
    }

    public function listFavorites(User $user): array
    {
        $favorites = $this->favoriteRepository->findBy(['user' => $user]);

        return array_map(function (Favorite $favorite) {
            $heroes = $favorite->getHeroes()->map(function (FavoriteHeroes $heroRelation) {
                return [
                    'id' => $heroRelation->getHeroId(),
                    'created_at' => $heroRelation->getCreatedAt(),
                ];
            })->toArray();

            usort($heroes, function ($a, $b) {
                return strtotime($b['created_at']->format('Y-m-d H:i:s')) - strtotime($a['created_at']->format('Y-m-d H:i:s'));
            });

            return [
                'id' => $favorite->getId(),
                'name' => $favorite->getName(),
                'heroes' => $heroes,
            ];
        }, $favorites);
    }

    public function getCurrentUser(?string $userId): ?User
    {
        if (!$userId) {
            return null;
        }

        return $this->userRepository->find($userId);
    }
}
