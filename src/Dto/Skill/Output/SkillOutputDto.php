<?php

namespace App\Dto\Skill\Output;

use App\Dto\DtoInterface;
use App\Entity\Skill;
use JsonSerializable;

class SkillOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $icon,
        public readonly int $yearsOfExperience,
        public readonly bool $isFeatured,
        public readonly int $projectCount
    ) {}

    public static function fromEntity(Skill $skill): self
    {
        return new self(
            id: $skill->getId(),
            name: $skill->getName(),
            icon: $skill->getIcon(),
            yearsOfExperience: $skill->getYearsOfExperience(),
            isFeatured: $skill->getIsFeatured(),
            projectCount: $skill->getProjects()->count()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'yearsOfExperience' => $this->yearsOfExperience,
            'isFeatured' => $this->isFeatured,
            'projectCount' => $this->projectCount,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
