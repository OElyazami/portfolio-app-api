<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProjectRepository::class)]
#[ORM\Table(name: 'projects')]
class Project
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\Length(max: 100)]
    private string $title;

    #[ORM\Column(length: 50)]
    #[Assert\Length(max: 50)]
    private ?string $slug = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'text')]
    private ?string $shortDescription = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        max: 255
    )]
    private ?string $featuredImage = null;

    #[ORM\Column()]
    private int $years = 0;

    #[ORM\Column()]
    private bool $isFeatured = false;

    #[ORM\ManyToMany(
        targetEntity: Category::class,
        mappedBy: 'projects',
        cascade: ['persist']
    )]
    private Collection $categories;

    #[ORM\ManyToOne(
        targetEntity: Company::class,
        inversedBy: 'projects',
        cascade: ['persist']
    )]
    #[ORM\JoinColumn(nullable: true)]
    private ?Company $company = null;

    #[ORM\ManyToOne(
        targetEntity: Client::class,
        inversedBy: 'projects',
        cascade: ['persist']
    )]
    #[ORM\JoinColumn(nullable: true)]
    private ?Client $client = null;

    #[ORM\ManyToOne(
        targetEntity: User::class,
        inversedBy: 'projects'
    )]
    #[ORM\JoinColumn(
        onDelete: 'CASCADE'
    )]
    private ?User $user = null;

    #[ORM\ManyToMany(
        targetEntity: Skill::class,
        mappedBy: 'projects',
        cascade: ['persist']
    )]
    private Collection $skills; 

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->skills = new ArrayCollection();
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
     * Get the value of slug
     *
     * @return ?string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @param ?string $slug
     *
     * @return self
     */
    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return ?string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param ?string $description
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of featuredImage
     *
     * @return string
     */
    public function getFeaturedImage(): ?string
    {
        return $this->featuredImage;
    }

    /**
     * Set the value of featuredImage
     *
     * @param ?string $featuredImage
     *
     * @return self
     */
    public function setFeaturedImage(?string $featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    /**
     * Get the value of years
     *
     * @return int
     */
    public function getYears(): int
    {
        return $this->years;
    }

    /**
     * Set the value of years
     *
     * @param int $years
     *
     * @return self
     */
    public function setYears(int $years): self
    {
        $this->years = $years;

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

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProject($this);
        }
        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeProject($this);
        }
        return $this;
    }

    /**
     * Get the value of company
     *
     * @return ?Company
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * Set the value of company
     *
     * @param ?Company $company
     *
     * @return self
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get the value of client
     *
     * @return ?Client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Set the value of client
     *
     * @param ?Client $client
     *
     * @return self
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of user
     *
     * @return User
     */
    public function getUser(): User
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

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skill $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->addProject($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): self
    {
        if ($this->skills->contains($skill)){
            $this->skills->removeElement($skill);
            $skill->removeProject($this);
        }

        return $this;
    }

    /**
     * Get the value of shortDescription
     *
     * @return ?string
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * Set the value of shortDescription
     *
     * @param ?string $shortDescription
     *
     * @return self
     */
    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
