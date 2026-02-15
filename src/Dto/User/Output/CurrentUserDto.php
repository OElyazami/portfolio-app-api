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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'avatarImage' => $this->avatarImage
        ];
    }
}