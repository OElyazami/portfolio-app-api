<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\Table(name: 'skills')]
class Skill {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Assert\Length(max: 25)]
    private string $name;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50)]
    private ?string $icon = null;

    #[ORM\Column()]
    private int $yearsOfExperience;

    #[ORM\Column()]
    private bool $isFeatured;

    #[ORM\ManyToMany(
        targetEntity: Project::class,
        inversedBy: 'skills'
    )]
    #[ORM\JoinTable(name: 'project_skill')]
    private Collection $projects;

    #[ORM\ManyToOne(
        targetEntity: User::class,
        inversedBy: 'skills'
    )]
    private ?User $user;

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
     * Set the value of id
     *
     * @param ?int $id
     *
     * @return self
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of icon
     *
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @param ?string $icon
     *
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the value of yearsOfExperience
     *
     * @return int
     */
    public function getYearsOfExperience(): int
    {
        return $this->yearsOfExperience;
    }

    /**
     * Set the value of yearsOfExperience
     *
     * @param int $yearsOfExperience
     *
     * @return self
     */
    public function setYearsOfExperience(int $yearsOfExperience): self
    {
        $this->yearsOfExperience = $yearsOfExperience;

        return $this;
    }

    /**
     * Get the value of isFeatured
     *
     * @return bool
     */
    public function getIsFeatured(): bool
    {
        return $this->isFeatured;
    }

    /**
     * Set the value of isFeatured
     *
     * @param bool $isFeatured
     *
     * @return self
     */
    public function setIsFeatured(bool $isFeatured): self
    {
        $this->isFeatured = $isFeatured;

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
        if (!$this->projects->contains($project)){
            $this->projects->add($project);
            $project->addSkill($this);
        }
        return $this;
    }
    
    public function removeProject(Project $project): self
    {
        if ($this->projects->contains($project)){
            $this->projects->removeElement($project);
            $project->removeSkill($this);
        }
        return $this;
    }

        /**
     * Get the value of user
     *
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param User $user
     *
     * @return self
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}