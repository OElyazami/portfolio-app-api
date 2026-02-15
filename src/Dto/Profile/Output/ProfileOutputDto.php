<?php

namespace App\Dto\Profile\Output;

use App\Dto\DtoInterface;
use App\Entity\Profile;
use JsonSerializable;

class ProfileOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $fullName,
        public readonly ?string $jobTitle,
        public readonly ?string $bio,
        public readonly ?string $avatarImage,
        public readonly ?string $website,
        public readonly ?string $address,
        public readonly ?string $contactEmail,
        public readonly ?string $mobileNumber,
        public readonly ?string $landLineNumber,
        public readonly ?string $linkedinUrl,
        public readonly ?string $githubUrl
    ) {}

    public static function fromEntity(Profile $profile): self
    {
        return new self(
            id: $profile->getId(),
            firstName: $profile->getFirstName(),
            lastName: $profile->getLastName(),
            fullName: $profile->getFullName(),
            jobTitle: $profile->getJobTitle(),
            bio: $profile->getBio(),
            avatarImage: $profile->getAvatarImage(),
            website: $profile->getWebsite(),
            address: $profile->getAddress(),
            contactEmail: $profile->getContactEmail(),
            mobileNumber: $profile->getMobileNumber(),
            landLineNumber: $profile->getLandLineNumber(),
            linkedinUrl: $profile->getLinkedinUrl(),
            githubUrl: $profile->getGithubUrl()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'fullName' => $this->fullName,
            'jobTitle' => $this->jobTitle,
            'bio' => $this->bio,
            'avatarImage' => $this->avatarImage,
            'website' => $this->website,
            'address' => $this->address,
            'contactEmail' => $this->contactEmail,
            'mobileNumber' => $this->mobileNumber,
            'landLineNumber' => $this->landLineNumber,
            'linkedinUrl' => $this->linkedinUrl,
            'githubUrl' => $this->githubUrl,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
