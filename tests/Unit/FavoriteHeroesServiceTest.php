<?php

use App\Entity\Favorite;
use App\Entity\FavoriteHeroes;
use App\Entity\User;
use App\Repository\FavoriteHeroesRepository;
use App\Service\FavoriteHeroesService;
use Doctrine\ORM\EntityManagerInterface;

beforeEach(function () {
    $this->entityManager = Mockery::mock(EntityManagerInterface::class);
    $this->favoriteRepository = Mockery::mock(App\Repository\FavoriteRepository::class);
    $this->favoriteHeroesRepository = Mockery::mock(FavoriteHeroesRepository::class);

    $this->entityManager->shouldReceive('getRepository')
        ->with(FavoriteHeroes::class)
        ->andReturn($this->favoriteHeroesRepository);

    $this->favoriteHeroesService = new FavoriteHeroesService($this->entityManager, $this->favoriteRepository);
});

// Fix for addHeroesToFavorite test

// Fix for addHeroesToFavorite test
test('addHeroesToFavorite adds heroes to a favorite list successfully', function () {
    $user = new User();
    $favoriteId = 1;
    $heroIds = [101, 102];
    $favorite = new Favorite();

    // Mock repository behavior
    $this->favoriteRepository->shouldReceive('findOneBy')
        ->with(['id' => $favoriteId, 'user' => $user])
        ->andReturn($favorite);

    // Mock the entity manager's getRepository call
    $this->entityManager->shouldReceive('getRepository')
        ->with(FavoriteHeroes::class)
        ->andReturn($this->favoriteHeroesRepository);

    $this->favoriteHeroesRepository
        ->shouldReceive('findOneBy')
        ->andReturn(null); // No existing favorite hero found

    $this->entityManager->shouldReceive('persist')->twice();
    $this->entityManager->shouldReceive('flush')->once();

    $result = $this->favoriteHeroesService->addHeroesToFavorite($user, $favoriteId, $heroIds);
    expect($result)->toBe([101, 102]);
});

test('addHeroesToFavorite throws exception when favorite not found', function () {
    $user = new User();
    $heroIds = [101, 102];

    $this->favoriteRepository
        ->shouldReceive('findOneBy')
        ->with(['id' => 1, 'user' => $user])
        ->andReturn(null);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Favorite not found or you don\'t have permission.');

    $this->favoriteHeroesService->addHeroesToFavorite($user, 1, $heroIds);
});

test('removeHeroFromFavorite removes a hero from a favorite successfully', function () {
    $user = new User();
    $favorite = new Favorite();
    $favorite->setUser($user);

    $favoriteHero = new FavoriteHeroes();
    $favoriteHero->setHeroId(101);

    $this->favoriteRepository
        ->shouldReceive('find')
        ->with(1)
        ->andReturn($favorite);

    $this->favoriteHeroesRepository
        ->shouldReceive('findOneBy')
        ->with(['favorite' => $favorite, 'hero_id' => 101])
        ->andReturn($favoriteHero);

    $this->entityManager->shouldReceive('remove')->once();
    $this->entityManager->shouldReceive('flush')->once();

    $this->favoriteHeroesService->removeHeroFromFavorite($user, 1, 101);
});

test('removeHeroFromFavorite throws exception when hero not found', function () {
    $user = new User();
    $favorite = new Favorite();
    $favorite->setUser($user);

    $this->favoriteRepository
        ->shouldReceive('find')
        ->with(1)
        ->andReturn($favorite);

    $this->favoriteHeroesRepository
        ->shouldReceive('findOneBy')
        ->with(['favorite' => $favorite, 'hero_id' => 101])
        ->andReturn(null);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage('Favorite Hero not found in current list.');

    $this->favoriteHeroesService->removeHeroFromFavorite($user, 1, 101);
});
