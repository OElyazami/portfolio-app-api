<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'clients')]
class Client {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        max: 25
    )]
    #[ORM\Column(nullable: false)]
    private string $name;

    #[Assert\Length(
        max: 255
    )]

    #[ORM\Column(nullable: true)]
    private ?string $logo_image = null;

    #[ORM\OneToMany(
        targetEntity: Project::class,
        mappedBy: 'client'
    )]
    private Collection $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    /**
     * Get the value of id
     *
     * @return ?int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of logo_image
     *
     * @return ?string
     */
    public function getLogoImage(): ?string
    {
        return $this->logo_image;
    }

    /**
     * Set the value of logo_image
     *
     * @param ?string $logo_image
     *
     * @return self
     */
    public function setLogoImage(?string $logo_image): self
    {
        $this->logo_image = $logo_image;

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

    /**
     * Set the value of projects
     *
     * @param Project $project
     *
     * @return self
     */
    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)){
            $this->projects->add($project);
            $project->setClient($this);
        }
        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)){
            $this->projects->removeElement($project);
            $project->setClient(null);
        }
        return $this;
    }
}