<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SkillRepository::class)]
#[ORM\Table(name: 'skills')]
class Skill {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    private ?string $name = null;
    private ?string $icon = null;
    private ?string $level = null;
    private string $category;
    private int $yearsOfExperience;
    private bool $isFeatured;


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
     * Get the value of level
     *
     * @return ?string
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * Set the value of level
     *
     * @param ?string $level
     *
     * @return self
     */
    public function setLevel(?string $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get the value of category
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @param string $category
     *
     * @return self
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;

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
}