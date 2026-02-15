<?php

namespace App\Dto\Category\Output;

use App\Dto\DtoInterface;
use App\Entity\Category;
use JsonSerializable;

class CategoryOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly ?string $description,
        public readonly string $color,
        public readonly ?string $icon,
        public readonly bool $isActive,
        public readonly int $projectCount
    ) {}

    public static function fromEntity(Category $category): self
    {
        return new self(
            id: $category->getId(),
            name: $category->getName(),
            slug: $category->getSlug(),
            description: $category->getDescription(),
            color: $category->getColor(),
            icon: $category->getIcon(),
            isActive: $category->getIsActive(),
            projectCount: $category->getProjects()->count()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'isActive' => $this->isActive,
            'projectCount' => $this->projectCount,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
