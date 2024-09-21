<?php

use App\Entity\User;
use App\Service\FavoriteService;
use Symfony\Component\HttpFoundation\RequestStack;

beforeEach(function () {
    $this->session = Mockery::mock(Symfony\Component\HttpFoundation\Session\SessionInterface::class);
    $this->requestStack = Mockery::mock(RequestStack::class);
    $this->requestStack->shouldReceive('getSession')->andReturn($this->session);

    $this->favoriteService = Mockery::mock(FavoriteService::class);

    $this->session->shouldReceive('get')->with('user_id')->andReturn(1);

    // Mock user retrieval
    $user = new User();
    $this->favoriteService->shouldReceive('getCurrentUser')->with(1)->andReturn($user);

    $this->controller = new App\Controller\FavoriteController($this->favoriteService, $this->requestStack);
});
