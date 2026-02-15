<?php

namespace App\Dto\Company\Output;

use App\Dto\DtoInterface;
use App\Entity\Company;
use JsonSerializable;

class CompanyOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $logoImage,
        public readonly int $projectCount
    ) {}

    public static function fromEntity(Company $company): self
    {
        return new self(
            id: $company->getId(),
            title: $company->getTitle(),
            description: $company->getDescription(),
            logoImage: $company->getLogoImage(),
            projectCount: $company->getProjects()->count()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'logoImage' => $this->logoImage,
            'projectCount' => $this->projectCount,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
