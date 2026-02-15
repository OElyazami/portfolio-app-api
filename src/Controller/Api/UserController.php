<?php

namespace App\Controller\Api;

use App\Dto\User\Input\UpdateUserDto;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: 'user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}

    #[Route(path: '/current', name: 'current', methods: ['GET'])]
    public function current(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->userService->getCurrentUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route(path: '/details', name: 'details', methods: ['GET'])]
    public function details(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->userService->getUserDetails($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    #[Route(path: '/update', name: 'update', methods: ['PUT'])]
    public function update(
        #[CurrentUser] User $user,
        #[MapRequestPayload] UpdateUserDto $userDto
    ): JsonResponse {
        $response = $this->userService->updateUser($userDto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }
}
