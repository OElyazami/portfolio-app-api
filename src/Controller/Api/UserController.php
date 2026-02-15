<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: 'user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Get current authenticated user (auth info only)
     */
    #[Route(path: '/current', name: 'current', methods: ['GET'])]
    public function current(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->userService->getCurrentUser($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }
}
