<?php

namespace App\Controller\Api;

use App\Dto\Profile\Input\CreateProfileDto;
use App\Dto\Profile\Input\UpdateProfileDto;
use App\Entity\User;
use App\Service\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: 'profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    /**
     * Get the authenticated user's portfolio profile
     */
    #[Route(path: '/me', name: 'me', methods: ['GET'])]
    public function me(#[CurrentUser] User $user): JsonResponse
    {
        $response = $this->profileService->getProfile($user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Get a user's public profile by user ID (for portfolio viewing)
     */
    #[Route(path: '/{userId}', name: 'show', methods: ['GET'], requirements: ['userId' => '\d+'])]
    public function show(int $userId): JsonResponse
    {
        $response = $this->profileService->getProfileByUserId($userId);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Create the authenticated user's portfolio profile
     */
    #[Route(path: '/create', name: 'create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateProfileDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->profileService->createProfile($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }

    /**
     * Update the authenticated user's portfolio profile
     */
    #[Route(path: '/update', name: 'update', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload] UpdateProfileDto $dto,
        #[CurrentUser] User $user
    ): JsonResponse {
        $response = $this->profileService->updateProfile($dto, $user);
        return $this->json($response->getArrayFormat(), $response->getCode());
    }
}
