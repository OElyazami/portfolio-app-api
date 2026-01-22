<?php

namespace App\Dto\Project\Output;

use App\Dto\DtoInterface;
use App\Entity\Project;
use JsonSerializable;

class ProjectListDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $shortDescription,
        public readonly ?string $featuredImage,
        public readonly int $years,
        public readonly bool $isFeatured,
        public readonly array $categories
        // public readonly string $createdAt
    ) {}

    public static function fromEntity(Project $project): self
    {
        return new self(
            id: $project->getId(),
            title: $project->getTitle(),
            slug: $project->getSlug(),
            shortDescription: $project->getShortDescription(),
            featuredImage: $project->getFeaturedImage(),
            years: $project->getYears(),
            isFeatured: $project->getIsFeatured(),
            categories: array_map(
                fn($category) => [
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'slug' => $category->getSlug()
                ],
                $project->getCategories()->toArray()
            )
            // createdAt: $project->getCreatedAt()->format('Y-m-d')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'shortDescription' => $this->shortDescription,
            'featuredImage' => $this->featuredImage,
            'years' => $this->years,
            'isFeatured' => $this->isFeatured,
            'categories' => $this->categories,
            // 'createdAt' => $this->createdAt
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}