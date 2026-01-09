<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\Table(name: 'projects')]
class Project {

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

    #[ORM\Column()]
    private ?string $featuredImage;

    #[ORM\Column()]
    private DateTime $years;

    #[ORM\Column()]
    private bool $isFeatured;

    #[ORM\ManyToMany(
        targetEntity: Category::class,
        mappedBy: 'projects',
        cascade:['persist']
    )]
    private Collection $categories;


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
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of featuredImage
     *
     * @return string
     */
    public function getFeaturedImage(): string
    {
        return $this->featuredImage;
    }

    /**
     * Set the value of featuredImage
     *
     * @param string $featuredImage
     *
     * @return self
     */
    public function setFeaturedImage(string $featuredImage): self
    {
        $this->featuredImage = $featuredImage;

        return $this;
    }

    /**
     * Get the value of years
     */ 
    public function getYears()
    {
        return $this->years;
    }

    /**
     * Set the value of years
     *
     * @return  self
     */ 
    public function setYears($years)
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
        if (!$this->categories->contains($category)){
            $this->categories->add($category);
            $category->addProject($this);
        }
        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)){
            $this->categories->removeElement($category);
            $category->removeProject($this);
        }
        return $this;
    }
}