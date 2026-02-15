<?php

namespace App\Dto\Category\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Name must be at least {{ limit }} characters long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $name = null,

        #[Assert\Length(max: 25)]
        #[Assert\Regex(
            pattern: '/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            message: 'Slug can only contain lowercase letters, numbers, and hyphens'
        )]
        public readonly ?string $slug = null,

        #[Assert\Length(max: 65535)]
        public readonly ?string $description = null,

        #[Assert\Length(max: 10)]
        public readonly ?string $color = null,

        #[Assert\Length(max: 50)]
        public readonly ?string $icon = null,

        #[Assert\Type(type: 'boolean')]
        public readonly ?bool $isActive = null
    ) {}
}
