<?php

namespace App\Dto\User\Output;

use App\Dto\DtoInterface;
use App\Entity\User;
use App\Entity\UserDetails;

class UserDetailsDto implements DtoInterface
{

    public function __construct(
        public readonly ?string $linkedinUrl,
        public readonly ?string $githubUrl,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $profile,
        public readonly ?string $email,
        public readonly ?string $address,
        public readonly ?string $mobileNumber,
        public readonly ?string $landLineNumber
    ) {}

    public static function fromEntity(User $user): self
    {
        if (!$user instanceof User) {
            throw new \InvalidArgumentException(
                sprintf('Expected User entity, got "%s"', get_class($user))
            );
        }

        $userDetails = $user->getUserDetails();

        return new self(
            linkedinUrl: $userDetails->getLinkedinUrl(),
            githubUrl: $userDetails->getGithubUrl(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            profile: $userDetails->getProfile(),
            email: $user->getEmail(),
            address: $userDetails->getAddress(),
            mobileNumber: $userDetails->getMobileNumber(),
            landLineNumber: $userDetails->getLandLineNumber()
        );
    }
}
