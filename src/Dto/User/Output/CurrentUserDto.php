<?php

namespace App\Dto\User\Output;

use App\Dto\DtoInterface;
use App\Entity\User;

class CurrentUserDto implements DtoInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $email,
        public readonly array $roles,
        public readonly bool $hasProfile
    ) {}

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            roles: $user->getRoles(),
            hasProfile: $user->getProfile() !== null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'roles' => $this->roles,
            'hasProfile' => $this->hasProfile,
        ];
    }
}