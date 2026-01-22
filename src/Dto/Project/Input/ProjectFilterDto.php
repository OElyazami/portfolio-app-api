<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectFilterDto implements DtoInterface
{
    public function __construct(
        #[Assert\Positive()]
        public readonly ?int $page = 1,

        #[Assert\Range(min: 1, max: 100)]
        public readonly ?int $limit = 10,

        #[Assert\Type(type: 'boolean')]
        public readonly ?bool $featured = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $category = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $skill = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $user = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $company = null,

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $client = null,

        #[Assert\Length(min: 2, max: 100)]
        public readonly ?string $search = null,

        #[Assert\Choice(['title', 'years', 'createdAt', 'updatedAt'])]
        public readonly ?string $sortBy = 'years',

        #[Assert\Choice(['ASC', 'DESC'])]
        public readonly ?string $sortOrder = 'DESC',

        #[Assert\Range(min: 0, max: 50)]
        public readonly ?int $minYears = null,

        #[Assert\Range(min: 0, max: 50)]
        public readonly ?int $maxYears = null
    ) {}
}