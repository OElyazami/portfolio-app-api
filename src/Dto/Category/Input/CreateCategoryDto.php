<?php

namespace App\Dto\Category\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCategoryDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Category name is required')]
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Name must be at least {{ limit }} characters long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $name,

        #[Assert\Length(
            max: 25,
            maxMessage: 'Slug cannot be longer than {{ limit }} characters'
        )]
        #[Assert\Regex(
            pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            message: 'Slug can only contain lowercase letters, numbers, and hyphens'
        )]
        public readonly ?string $slug = null,

        #[Assert\Length(
            max: 65535,
            maxMessage: 'Description cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $description = null,

        #[Assert\NotBlank(message: 'Color is required')]
        #[Assert\Length(
            max: 10,
            maxMessage: 'Color cannot be longer than {{ limit }} characters'
        )]
        public readonly string $color = '#000000',

        #[Assert\Length(
            max: 50,
            maxMessage: 'Icon cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $icon = null,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $isActive = true
    ) {}
}
