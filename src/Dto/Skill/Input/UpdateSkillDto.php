<?php

namespace App\Dto\Skill\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateSkillDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 1,
            max: 25,
            minMessage: 'Name must be at least {{ limit }} character long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $name = null,

        #[Assert\Length(
            max: 50,
            maxMessage: 'Icon cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $icon = null,

        #[Assert\Range(
            min: 0,
            max: 50,
            notInRangeMessage: 'Years of experience must be between {{ min }} and {{ max }}'
        )]
        public readonly ?int $yearsOfExperience = null,

        #[Assert\Type(type: 'boolean', message: 'Featured status must be true or false')]
        public readonly ?bool $isFeatured = null
    ) {}
}
