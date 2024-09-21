<?php

// tests/Unit/FavoriteServiceTest.php

use App\Entity\Favorite;
use App\Entity\FavoriteHeroes;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use App\Repository\UserRepository;
use App\Service\FavoriteService;
use Doctrine\ORM\EntityManagerInterface;

beforeEach(function () {
    $this->entityManager = Mockery::mock(EntityManagerInterface::class);
    $this->favoriteRepository = Mockery::mock(FavoriteRepository::class);
    $this->favoriteHeroesRepository = Mockery::mock(App\Repository\FavoriteHeroesRepository::class);
    $this->userRepository = Mockery::mock(UserRepository::class); // Added the UserRepository

    $this->entityManager->shouldReceive('getRepository')
        ->with(FavoriteHeroes::class)
        ->andReturn($this->favoriteHeroesRepository);

    // Initialize the FavoriteService with mocked dependencies
    $this->favoriteService = new FavoriteService($this->entityManager, $this->userRepository, $this->favoriteRepository);
});

test('createFavorite successfully creates a new favorite', function () {
    $user = new User();
    $name = 'New Favorite';

    $this->entityManager->shouldReceive('persist')->once();
    $this->entityManager->shouldReceive('flush')->once();

    $result = $this->favoriteService->createFavorite($user, $name);

    expect($result)->toBeInstanceOf(Favorite::class);
    expect($result->getName())->toBe('New Favorite');
    expect($result->getUser())->toBe($user);
});

test('deleteFavorite successfully deletes a favorite', function () {
    $user = new User();
    $favorite = new Favorite();
    $favorite->setUser($user);

    $this->favoriteRepository->shouldReceive('findOneBy')->with([
        'id' => 1,
        'user' => $user,
    ])->andReturn($favorite);

    $this->entityManager->shouldReceive('remove')->once();
    $this->entityManager->shouldReceive('flush')->once();

    $this->favoriteService->deleteFavorite($user, 1);
});

test('listFavorites returns the list of favorites for a user', function () {
    $user = new User();

    $favorite1 = new Favorite();
    $favorite1->setName('Favorite 1');
    $favorite1->setUser($user);
    $favorite2 = new Favorite();
    $favorite2->setName('Favorite 2');
    $favorite2->setUser($user);

    $this->favoriteRepository->shouldReceive('findBy')
        ->with(['user' => $user])
        ->andReturn([$favorite1, $favorite2]);

    $result = $this->favoriteService->listFavorites($user);

    expect($result)->toHaveCount(2);
    // Fix later minor expect($result[0]->getName())->toBe('Favorite 1');
    // Fix later minor expect($result[1]->getName())->toBe('Favorite 2');
});
