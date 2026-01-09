<?php

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class JwtService
{
    private string $secret;
    private string $algorithm = 'HS256';
    private int $ttl;

    public function __construct(ParameterBagInterface $params)
    {
        $this->secret = $params->get('app.jwt_secret');
        $this->ttl = $params->get('app.jwt_ttl') ?? 3600;
    }

    public function generateToken(User $user): array
    {
        $issuedAt = time();
        $expiration = $issuedAt + $this->ttl;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expiration,
            'sub' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];

        $token = JWT::encode($payload, $this->secret, $this->algorithm);

        return [
            'token' => $token,
            'expires_at' => $expiration,
            'type' => 'Bearer'
        ];
    }

    public function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new \RuntimeException('Invalid token: ' . $e->getMessage());
        }
    }

    public function extractTokenFromHeader(string $authorizationHeader): ?string
    {
        if (str_starts_with($authorizationHeader, 'Bearer ')) {
            return substr($authorizationHeader, 7);
        }

        return null;
    }

    public function getPayload(string $token): array
    {
        return $this->validateToken($token);
    }

    public function isTokenExpired(array $payload): bool
    {
        $expiration = $payload['exp'] ?? 0;
        return time() >= $expiration;
    }
}