<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectExportDto implements DtoInterface
{
    public function __construct(
        #[Assert\Choice(['json', 'csv', 'xml', 'pdf'])]
        public readonly string $format = 'json',

        #[Assert\Type(type: 'boolean')]
        public readonly bool $includeDetails = true,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $includeCategories = true,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $includeSkills = true,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $includeRelations = true,

        #[Assert\Choice(['all', 'featured', 'user', 'category', 'skill'])]
        public readonly string $filter = 'all',

        #[Assert\Type(type: 'int')]
        #[Assert\Positive()]
        public readonly ?int $filterId = null,

        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly ?string $startDate = null,

        #[Assert\DateTime(format: 'Y-m-d')]
        public readonly ?string $endDate = null
    ) {}
}