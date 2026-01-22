<?php

namespace App\Dto\Project\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ProjectImportDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotNull(message: 'Projects data is required')]
        #[Assert\Count(min: 1, max: 1000)]
        #[Assert\All([
            new Assert\Collection([
                'fields' => [
                    'title' => [
                        new Assert\NotBlank(),
                        new Assert\Length(min: 3, max: 100)
                    ],
                    'description' => [
                        new Assert\NotBlank(),
                        new Assert\Length(min: 50, max: 65535)
                    ],
                    'shortDescription' => [
                        new Assert\NotBlank(),
                        new Assert\Length(min: 20, max: 500)
                    ],
                    'years' => [
                        new Assert\NotNull(),
                        new Assert\Range(min: 0, max: 50)
                    ],
                    'userId' => [
                        new Assert\NotNull(),
                        new Assert\Type(type: 'int'),
                        new Assert\Positive()
                    ]
                ],
                'allowExtraFields' => true,
                'allowMissingFields' => false
            ])
        ])]
        public readonly array $projects,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $skipDuplicates = true,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $updateExisting = false,

        #[Assert\Type(type: 'boolean')]
        public readonly bool $notifyUsers = false
    ) {}
}