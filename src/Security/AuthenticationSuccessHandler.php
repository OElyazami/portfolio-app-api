<?php

namespace App\Security;

use App\Entity\User;
use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private JwtService $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $token->getUser();
        $tokenData = $this->jwtService->generateToken($user);

        return new JsonResponse([
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'roles' => $user->getRoles(),
            ],
            'access_token' => $tokenData['token'],
            'token_type' => $tokenData['type'],
            'expires_at' => $tokenData['expires_at'],
            'expires_in' => $tokenData['expires_at'] - time(),
        ]);
    }
}