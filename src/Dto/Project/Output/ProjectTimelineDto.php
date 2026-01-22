<?php

namespace App\Dto\Project\Output;

use App\Dto\DtoInterface;
use JsonSerializable;

class ProjectTimelineDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $year,
        public readonly array $projects,
        public readonly int $projectCount,
        public readonly array $topSkills,
        public readonly array $topCategories
    ) {}

    public function toArray(): array
    {
        return [
            'year' => $this->year,
            'projects' => $this->projects,
            'projectCount' => $this->projectCount,
            'topSkills' => $this->topSkills,
            'topCategories' => $this->topCategories
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}