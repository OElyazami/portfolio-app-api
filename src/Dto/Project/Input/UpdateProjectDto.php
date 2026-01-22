<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateProjectDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 3,
            max: 100,
            minMessage: 'Title must be at least {{ limit }} characters long',
            maxMessage: 'Title cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $title = null,

        #[Assert\Length(max: 50)]
        #[Assert\Regex(
            pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            message: 'Slug can only contain lowercase letters, numbers, and hyphens'
        )]
        public readonly ?string $slug = null,

        #[Assert\Length(
            min: 50,
            max: 65535,
            minMessage: 'Description must be at least {{ limit }} characters long',
            maxMessage: 'Description cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $description = null,

        #[Assert\Length(
            min: 20,
            max: 500,
            minMessage: 'Short description must be at least {{ limit }} characters long',
            maxMessage: 'Short description cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $shortDescription = null,

        #[Assert\Url(message: 'Featured image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $featuredImage = null,

        #[Assert\Range(
            min: 0,
            max: 50,
            notInRangeMessage: 'Years must be between {{ min }} and {{ max }}'
        )]
        public readonly ?int $years = null,

        #[Assert\Type(type: 'boolean', message: 'Featured status must be true or false')]
        public readonly ?bool $isFeatured = null,

        #[Assert\Count(
            min: 1,
            max: 10,
            minMessage: 'You must select at least {{ limit }} category',
            maxMessage: 'You cannot select more than {{ limit }} categories'
        )]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $categories = null,

        #[Assert\Count(
            min: 1,
            max: 20,
            minMessage: 'You must select at least {{ limit }} skill',
            maxMessage: 'You cannot select more than {{ limit }} skills'
        )]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $skills = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $companyId = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $clientId = null
    ) {}
}