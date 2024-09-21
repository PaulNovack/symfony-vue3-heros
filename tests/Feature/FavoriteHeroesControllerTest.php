<?php

use App\Entity\Favorite;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\FavoriteHeroesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

beforeEach(function () {
    // Mock session and request stack
    $this->session = Mockery::mock(Symfony\Component\HttpFoundation\Session\SessionInterface::class);
    $this->requestStack = Mockery::mock(RequestStack::class);
    $this->requestStack->shouldReceive('getSession')->andReturn($this->session);

    // Mock UserRepository and service
    $this->userRepository = Mockery::mock(UserRepository::class);
    $this->favoriteHeroesService = Mockery::mock(FavoriteHeroesService::class);

    // Create the controller with mocks
    $this->controller = new App\Controller\FavoriteHeroesController(
        $this->requestStack,
        $this->userRepository,
        $this->favoriteHeroesService
    );
});

test('returns error when removing a non-existing hero from a favorite', function () {
    $user = new User();

    // Set up session expectations
    $this->session->shouldReceive('get')->with('user_id')->andReturn(1);
    $this->userRepository->shouldReceive('find')->with(1)->andReturn($user);

    // Simulate favorite not found
    $this->favoriteHeroesService->shouldReceive('removeHeroFromFavorite')->andThrow(new Exception('Hero not found'));

    $response = $this->controller->removeHeroFromFavorite(1, 101);
    expect($response->getStatusCode())->toBe(404);
    expect(json_decode($response->getContent(), true))->toBe(['error' => 'Hero not found']);
});
