<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'companies')]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        max: 25
    )]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\OneToMany(
        targetEntity: Project::class,
        mappedBy: 'company',
        cascade: ['persist']
    )]
    private Collection $projects;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255
    )]
    private ?string $logoImage;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }


    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the value of title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of projects
     *
     * @return Collection
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }


    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setCompany($this);
        }
        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)) {
            $this->projects->removeElement($project);
            $project->setCompany(null);
        }

        return $this;
    }

    /**
     * Get the value of logoImage
     *
     * @return ?string
     */
    public function getLogoImage(): ?string
    {
        return $this->logoImage;
    }

    /**
     * Set the value of logoImage
     *
     * @param ?string $logoImage
     *
     * @return self
     */
    public function setLogoImage(?string $logoImage): self
    {
        $this->logoImage = $logoImage;

        return $this;
    }
}
