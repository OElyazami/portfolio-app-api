<?php

namespace App\Dto\Project\Output;

use App\Dto\DtoInterface;
use App\Entity\Project;
use JsonSerializable;

class ProjectOutputDto implements DtoInterface, JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $shortDescription,
        public readonly ?string $featuredImage,
        public readonly int $years,
        public readonly bool $isFeatured,
        public readonly array $categories,
        public readonly array $skills,
        public readonly ?array $company,
        public readonly ?array $client,
        public readonly array $user
        // public readonly string $createdAt,
        // public readonly string $updatedAt
    ) {}

    public static function fromEntity(Project $project): self
    {
        // Assuming you have createdAt and updatedAt fields in your entity
        // If not, you can remove those parameters
        
        return new self(
            id: $project->getId(),
            title: $project->getTitle(),
            slug: $project->getSlug(),
            description: $project->getDescription(),
            shortDescription: $project->getShortDescription(),
            featuredImage: $project->getFeaturedImage(),
            years: $project->getYears(),
            isFeatured: $project->getIsFeatured(),
            categories: array_map(
                fn($category) => [
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'slug' => $category->getSlug(),
                    'description' => $category->getDescription()
                ],
                $project->getCategories()->toArray()
            ),
            skills: array_map(
                fn($skill) => [
                    'id' => $skill->getId(),
                    'name' => $skill->getName(),
                    'icon' => $skill->getIcon(),
                    'yearsOfExperience' => $skill->getYearsOfExperience()
                ],
                $project->getSkills()->toArray()
            ),
            company: $project->getCompany() ? [
                'id' => $project->getCompany()->getId(),
                'title' => $project->getCompany()->getTitle(),
                'logoImage' => $project->getCompany()->getLogoImage()
            ] : null,
            client: $project->getClient() ? [
                'id' => $project->getClient()->getId(),
                'name' => $project->getClient()->getName()
            ] : null,
            user: [
                'id' => $project->getUser()->getId(),
                'email' => $project->getUser()->getEmail(),
                'firstName' => $project->getUser()->getProfile()?->getFirstName(),
                'lastName' => $project->getUser()->getProfile()?->getLastName(),
                'avatar' => $project->getUser()->getProfile()?->getAvatarImage()
            ]
            // createdAt: $project->getCreatedAt()->format('Y-m-d H:i:s'),
            // updatedAt: $project->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'shortDescription' => $this->shortDescription,
            'featuredImage' => $this->featuredImage,
            'years' => $this->years,
            'isFeatured' => $this->isFeatured,
            'categories' => $this->categories,
            'skills' => $this->skills,
            'company' => $this->company,
            'client' => $this->client,
            'user' => $this->user,
            // 'createdAt' => $this->createdAt,
            // 'updatedAt' => $this->updatedAt
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}