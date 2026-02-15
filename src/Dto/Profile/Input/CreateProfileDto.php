<?php

namespace App\Dto\Profile\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProfileDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'First name is required')]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'First name must be at least {{ limit }} characters long',
            maxMessage: 'First name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $firstName,

        #[Assert\NotBlank(message: 'Last name is required')]
        #[Assert\Length(
            min: 2,
            max: 255,
            minMessage: 'Last name must be at least {{ limit }} characters long',
            maxMessage: 'Last name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $lastName,

        #[Assert\Length(
            max: 100,
            maxMessage: 'Job title cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $jobTitle = null,

        public readonly ?string $bio = null,

        #[Assert\Url(message: 'Avatar image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $avatarImage = null,

        #[Assert\Url(message: 'Website must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $website = null,

        #[Assert\Length(max: 255)]
        public readonly ?string $address = null,

        #[Assert\Email(message: 'Contact email must be a valid email')]
        public readonly ?string $contactEmail = null,

        #[Assert\Regex(
            pattern: '/^\+?[0-9\s\-\(\)]{10,}$/',
            message: 'Please enter a valid mobile number'
        )]
        #[Assert\Length(max: 20)]
        public readonly ?string $mobileNumber = null,

        #[Assert\Regex(
            pattern: '/^\+?[0-9\s\-\(\)]{10,}$/',
            message: 'Please enter a valid landline number'
        )]
        #[Assert\Length(max: 20)]
        public readonly ?string $landLineNumber = null,

        #[Assert\Url(message: 'LinkedIn URL must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $linkedinUrl = null,

        #[Assert\Url(message: 'GitHub URL must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $githubUrl = null
    ) {}
}
