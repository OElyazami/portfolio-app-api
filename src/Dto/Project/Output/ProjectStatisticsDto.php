<?php

namespace App\Dto\Project\Output;

use App\Dto\DtoInterface;
use JsonSerializable;

class ProjectStatisticsDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $totalProjects,
        public readonly int $featuredProjects,
        public readonly float $averageYears,
        public readonly array $projectsByYear,
        public readonly array $topCategories,
        public readonly array $topSkills,
        public readonly array $yearlyGrowth,
        public readonly int $projectsThisMonth
    ) {}

    public function toArray(): array
    {
        return [
            'totalProjects' => $this->totalProjects,
            'featuredProjects' => $this->featuredProjects,
            'averageYears' => $this->averageYears,
            'projectsByYear' => $this->projectsByYear,
            'topCategories' => $this->topCategories,
            'topSkills' => $this->topSkills,
            'yearlyGrowth' => $this->yearlyGrowth,
            'projectsThisMonth' => $this->projectsThisMonth
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}