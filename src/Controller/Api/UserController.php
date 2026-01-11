<?php

namespace App\Controller\Api;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: 'user', name: 'user_')]
class UserController extends AbstractController
{

    #[Route(path: '/current', name: 'current', methods: ['GET'])]
    public function current(#[CurrentUser] $user, UserService $userService): JsonResponse
    {
        $currentUser = $userService->getCurrentUser($user);
        return $this->json($currentUser, Response::HTTP_OK);
    }

    #[Route(path: '/details', name: 'details', methods: ['GET'])]
    public function details(#[CurrentUser] $user, UserService $userService): JsonResponse
    {
        $userDetails = $userService->getUserDetails($user);
        return $this->json($userDetails, Response::HTTP_OK);
    }

    #[Route(path: '/update', name: 'update', methods: ['PUT'])]
    public function update(#[CurrentUser] $user, UserService $userService): JsonResponse
    {
        $userDetails = $userService->updateUser($user);
        return $this->json($userDetails, Response::HTTP_OK);
    }
}
