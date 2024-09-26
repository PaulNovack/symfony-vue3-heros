<?php

namespace App\Service;

use App\Entity\Favorite;
use App\Entity\FavoriteHeroes;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteHeroesService
{
    private EntityManagerInterface $entityManager;
    private FavoriteRepository $favoriteRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        FavoriteRepository $favoriteRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * Adds heroes to a favorite list.
     */
    public function addHeroesToFavorite(User $user, int $favoriteId, array $heroIds): array
    {
        $favorite = $this->favoriteRepository->findOneBy([
            'id' => $favoriteId,
            'user' => $user,
        ]);

        if (!$favorite) {
            throw new \Exception('Favorite not found or you don\'t have permission.');
        }

        $addedHeroes = [];

        foreach ($heroIds as $heroId) {
            $existingFavoriteHero = $this->entityManager->getRepository(FavoriteHeroes::class)->findOneBy([
                'favorite_id' => $favorite,
                'hero_id' => $heroId,
            ]);

            if (!$existingFavoriteHero) {
                $favoriteHero = new FavoriteHeroes();
                $favoriteHero->setFavorite($favorite);
                $favoriteHero->setHeroId($heroId);
                $favoriteHero->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($favoriteHero);
                $addedHeroes[] = $heroId;
            }
        }

        $this->entityManager->flush();

        return $addedHeroes;
    }

    /**
     * Removes a hero from a favorite list.
     *
     * @throws \Exception
     */
    public function removeHeroFromFavorite(User $user, int $favoriteId, int $heroId): void
    {
        $favorite = $this->favoriteRepository->find($favoriteId);

        if (!$favorite || $favorite->getUser() !== $user) {
            throw new \Exception('Favorite not found or you don\'t have permission.');
        }

        $favoriteHero = $this->entityManager->getRepository(FavoriteHeroes::class)->findOneBy([
            'favorite' => $favorite,
            'hero_id' => $heroId,
        ]);

        if (!$favoriteHero) {
            throw new \Exception('Favorite Hero not found in current list.');
        }

        $this->entityManager->remove($favoriteHero);
        $this->entityManager->flush();
    }
}
