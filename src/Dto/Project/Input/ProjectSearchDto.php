<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectSearchDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Search query is required')]
        #[Assert\Length(min: 2, max: 100)]
        public readonly string $query,

        #[Assert\Range(min: 1, max: 100)]
        public readonly ?int $limit = 20,

        #[Assert\Type(type: 'boolean')]
        public readonly ?bool $includeDescription = true,

        #[Assert\Type(type: 'boolean')]
        public readonly ?bool $matchWholeWords = false,

        #[Assert\Choice(['relevance', 'title', 'years'])]
        public readonly ?string $sortBy = 'relevance'
    ) {}
}