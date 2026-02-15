<?php

namespace App\Dto\Certification\Output;

use App\Dto\DtoInterface;
use App\Entity\Certification;
use JsonSerializable;

class CertificationOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?string $description
    ) {}

    public static function fromEntity(Certification $certification): self
    {
        return new self(
            id: $certification->getId(),
            title: $certification->getTitle(),
            description: $certification->getDescription()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
