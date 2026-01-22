<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProjectDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Project title is required')]
        #[Assert\Length(
            min: 3,
            max: 100,
            minMessage: 'Title must be at least {{ limit }} characters long',
            maxMessage: 'Title cannot be longer than {{ limit }} characters'
        )]
        public readonly string $title,

        #[Assert\Length(
            max: 50,
            maxMessage: 'Slug cannot be longer than {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            message: 'Slug can only contain lowercase letters, numbers, and hyphens'
        )]
        public readonly ?string $slug = null,

        #[Assert\NotBlank(message: 'Project description is required')]
        #[Assert\Length(
            min: 50,
            max: 65535, // MySQL TEXT column limit
            minMessage: 'Description must be at least {{ limit }} characters long',
            maxMessage: 'Description cannot be longer than {{ limit }} characters'
        )]
        public readonly string $description,

        #[Assert\NotBlank(message: 'Short description is required')]
        #[Assert\Length(
            min: 20,
            max: 500,
            minMessage: 'Short description must be at least {{ limit }} characters long',
            maxMessage: 'Short description cannot be longer than {{ limit }} characters'
        )]
        public readonly string $shortDescription,

        #[Assert\Url(message: 'Featured image must be a valid URL')]
        #[Assert\Length(
            max: 255,
            maxMessage: 'Featured image URL cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $featuredImage = null,

        #[Assert\NotNull(message: 'Years of experience is required')]
        #[Assert\Range(
            min: 0,
            max: 50,
            notInRangeMessage: 'Years must be between {{ min }} and {{ max }}'
        )]
        public readonly int $years,

        #[Assert\NotNull(message: 'Featured status is required')]
        #[Assert\Type(type: 'boolean', message: 'Featured status must be true or false')]
        public readonly bool $isFeatured = false,

        #[Assert\NotNull(message: 'Categories are required')]
        #[Assert\Count(
            min: 1,
            max: 10,
            minMessage: 'You must select at least {{ limit }} category',
            maxMessage: 'You cannot select more than {{ limit }} categories'
        )]
        #[Assert\All([
            new Assert\Type(type: 'int', message: 'Each category ID must be an integer'),
            new Assert\Positive(message: 'Category ID must be positive')
        ])]
        public readonly array $categories = [],

        #[Assert\NotNull(message: 'Skills are required')]
        #[Assert\Count(
            min: 1,
            max: 20,
            minMessage: 'You must select at least {{ limit }} skill',
            maxMessage: 'You cannot select more than {{ limit }} skills'
        )]
        #[Assert\All([
            new Assert\Type(type: 'int', message: 'Each skill ID must be an integer'),
            new Assert\Positive(message: 'Skill ID must be positive')
        ])]
        public readonly array $skills = [],

        #[Assert\Type(type: 'int', message: 'Company ID must be an integer')]
        #[Assert\Positive(message: 'Company ID must be positive')]
        public readonly ?int $companyId = null,

        #[Assert\Type(type: 'int', message: 'Client ID must be an integer')]
        #[Assert\Positive(message: 'Client ID must be positive')]
        public readonly ?int $clientId = null
    ) {}
}