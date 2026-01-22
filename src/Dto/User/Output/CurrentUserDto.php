<?php

namespace App\Dto\User\Output;

use App\Dto\DtoInterface;
use App\Entity\User;

class CurrentUserDto implements DtoInterface {

    public function __construct(
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $avatarImage
    )
    {}

    public static function fromEntity(User $user): self
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(
                sprintf('Expected User entity, got "%s"', get_class($user))
            );
        }

        return new self(
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            email: $user->getEmail(),
            avatarImage: '',
        );
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'firstName' => $this->email,
            'lastName' => $this->lastName,
            'avatarImage' => $this->avatarImage
        ];
    }
}