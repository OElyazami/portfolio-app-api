<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BulkUpdateProjectDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotNull(message: 'Project IDs are required')]
        #[Assert\Count(min: 1, max: 100)]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly array $projectIds,

        #[Assert\Type(type: 'boolean')]
        public readonly ?bool $isFeatured = null,

        #[Assert\Range(min: 0, max: 50)]
        public readonly ?int $years = null,

        #[Assert\Count(min: 1, max: 10)]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $addCategories = null,

        #[Assert\Count(min: 1, max: 10)]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $removeCategories = null,

        #[Assert\Count(min: 1, max: 20)]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $addSkills = null,

        #[Assert\Count(min: 1, max: 20)]
        #[Assert\All([
            new Assert\Type(type: 'int'),
            new Assert\Positive()
        ])]
        public readonly ?array $removeSkills = null
    ) {}
}