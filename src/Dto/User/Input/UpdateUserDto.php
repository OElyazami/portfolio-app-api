<?php

namespace App\Dto\User\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserDto implements DtoInterface {

    public function __construct(
        #[Assert\NotBlank(message: 'First name is required')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'First name must be at least {{ limit }} characters long',
            maxMessage: 'First name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $firstName,

        #[Assert\NotBlank(message: 'Last name is required')]
        #[Assert\Length(
            min: 2,
            max: 50,
            minMessage: 'Last name must be at least {{ limit }} characters long',
            maxMessage: 'Last name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $lastName,

        #[Assert\NotBlank(message: 'Profile is required')]
        #[Assert\Length(
            min: 5,
            max: 100,
            minMessage: 'Profile must be at least {{ limit }} characters long',
            maxMessage: 'Profile cannot be longer than {{ limit }} characters'
        )]
        public readonly string $profile,

        #[Assert\NotBlank(message: 'Email is required')]
        #[Assert\Email(message: 'The email {{ value }} is not a valid email.')]
        #[Assert\Length(
            max: 180,
            maxMessage: 'Email cannot be longer than {{ limit }} characters'
        )]
        public readonly string $email,

        #[Assert\NotBlank(message: 'Address is required')]
        #[Assert\Length(
            min: 10,
            max: 255,
            minMessage: 'Address must be at least {{ limit }} characters long',
            maxMessage: 'Address cannot be longer than {{ limit }} characters'
        )]
        public readonly string $address,

        #[Assert\NotBlank(message: 'Mobile number is required')]
        #[Assert\Regex(
            pattern: '/^\+?[0-9\s\-\(\)]{10,}$/',
            message: 'Please enter a valid mobile number'
        )]
        #[Assert\Length(
            min: 10,
            max: 20,
            minMessage: 'Mobile number must be at least {{ limit }} characters',
            maxMessage: 'Mobile number cannot be longer than {{ limit }} characters'
        )]
        public readonly string $mobileNumber,

        #[Assert\Regex(
            pattern: '/^\+?[0-9\s\-\(\)]{10,}$/',
            message: 'Please enter a valid landline number'
        )]
        #[Assert\Length(
            min: 10,
            max: 20,
            minMessage: 'Landline number must be at least {{ limit }} characters',
            maxMessage: 'Landline number cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $landLineNumber,

        #[Assert\Url(message: 'The LinkedIn URL {{ value }} is not a valid URL')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'LinkedIn URL cannot be longer than {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/linkedin\.com\/in\//',
            message: 'LinkedIn URL must be a valid LinkedIn profile URL'
        )]
        public readonly string $linkedinUrl,

        #[Assert\Url(message: 'The GitHub URL {{ value }} is not a valid URL')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'GitHub URL cannot be longer than {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/github\.com\//',
            message: 'GitHub URL must be a valid GitHub profile URL'
        )]
        public readonly string $githubUrl
    ) {}
}