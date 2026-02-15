<?php

namespace App\Dto\Client\Output;

use App\Dto\DtoInterface;
use App\Entity\Client;
use JsonSerializable;

class ClientOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $logoImage,
        public readonly int $projectCount
    ) {}

    public static function fromEntity(Client $client): self
    {
        return new self(
            id: $client->getId(),
            name: $client->getName(),
            logoImage: $client->getLogoImage(),
            projectCount: $client->getProjects()->count()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logoImage' => $this->logoImage,
            'projectCount' => $this->projectCount,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
