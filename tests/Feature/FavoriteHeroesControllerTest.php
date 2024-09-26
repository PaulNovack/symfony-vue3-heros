<?php

use App\Entity\User;
use App\Service\FavoriteHeroesService;
use App\Service\UserService;
use App\Controller\FavoriteHeroesController;
use Symfony\Component\HttpFoundation\Request;
use Mockery;
use Exception;

beforeEach(function () {
    // Mock UserService
    $this->userService = Mockery::mock(UserService::class);

    // Mock FavoriteHeroesService
    $this->favoriteHeroesService = Mockery::mock(FavoriteHeroesService::class);

    // Mock the Request object
    $this->request = Mockery::mock(Request::class);

    // Create the controller with mocks
    $this->controller = new FavoriteHeroesController(
        $this->userService,
        $this->favoriteHeroesService
    );
});

test('returns error when removing a non-existing hero from a favorite', function () {
    $user = new User();

    // Set up UserService to return a user
    $this->userService->shouldReceive('getCurrentUser')
        ->with($this->request)
        ->andReturn($user);

    // Simulate favorite not found by throwing an exception in the service
    $this->favoriteHeroesService->shouldReceive('removeHeroFromFavorite')
        ->with($user, 1, 101)
        ->andThrow(new Exception('Hero not found'));

    // Call the method and capture the response
    $response = $this->controller->removeHeroFromFavorite(1, 101, $this->request);

    // Check response status and content
    expect($response->getStatusCode())->toBe(404);
    expect(json_decode($response->getContent(), true))->toBe(['error' => 'Hero not found']);
});
