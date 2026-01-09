<?php

namespace App\Security;

use App\Service\JwtService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use App\Logger\Logger;

class JwtAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private JwtService $jwtService, 
        private UserProviderInterface $userProvider,
        private Logger $logger)
    {
    }

    public function supports(Request $request): ?bool
    {
        $this->logger->info("checking support");
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authorizationHeader = $request->headers->get('Authorization', '');
        $this->logger->info("checking authenticate");
        if (empty($authorizationHeader)) {
            throw new CustomUserMessageAuthenticationException('Authorization header is required');
        }

        $token = $this->jwtService->extractTokenFromHeader($authorizationHeader);
        
        if (!$token) {
            throw new CustomUserMessageAuthenticationException('Invalid authorization header format. Expected: Bearer {token}');
        }

        $this->logger->info("checking authenticate token: $token");

        try {
            $payload = $this->jwtService->validateToken($token);
            
            if ($this->jwtService->isTokenExpired($payload)) {
                throw new CustomUserMessageAuthenticationException('Token has expired');
            }

            $userId = $payload['sub'] ?? null;
            
            if (!$userId) {
                throw new CustomUserMessageAuthenticationException('Invalid token payload: missing user identifier');
            }

            return new SelfValidatingPassport(
                new UserBadge($userId, function ($userId) {
                    return $this->userProvider->loadUserByIdentifier($userId);
                })
            );
        } catch (\RuntimeException $e) {
            throw new CustomUserMessageAuthenticationException($e->getMessage());
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'error' => 'Authentication failed',
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            'code' => Response::HTTP_UNAUTHORIZED
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        $data = [
            'message' => 'Authentication Required',
            'status' => 'error',
            'code' => Response::HTTP_UNAUTHORIZED
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}