<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table('user_details')]
#[ORM\Entity]
class UserDetails
{
    #[ORM\Column]
    #[ORM\Id]
    #[ORM\GeneratedValue()]
    private ?int $id = null;
    
    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 255
    )]
    private ?string $address = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $profile = null;

    #[ORM\Column(nullable: true)]
    private ?string $mobileNumber = null;

    #[ORM\Column(nullable: true)]
    private ?string $landLineNumber = null;
    
    #[ORM\OneToOne(inversedBy: 'userDetails', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', unique: true, nullable: false)]
    private User $user;

    #[ORM\Column(nullable: true)]
    private ?string $linkedinUrl = null;

    #[ORM\Column(nullable: true)]
    private ?string $githubUrl = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Email]
    private ?string $email = null;

    /**
     * Get the value of address
     *
     * @return ?string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param ?string $address
     *
     * @return self
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of profile
     *
     * @return ?string
     */
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    /**
     * Set the value of profile
     *
     * @param ?string $profile
     *
     * @return self
     */
    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get the value of mobileNumber
     *
     * @return ?string
     */
    public function getMobileNumber(): ?string
    {
        return $this->mobileNumber;
    }

    /**
     * Set the value of mobileNumber
     *
     * @param ?string $mobileNumber
     *
     * @return self
     */
    public function setMobileNumber(?string $mobileNumber): self
    {
        $this->mobileNumber = $mobileNumber;

        return $this;
    }

    /**
     * Get the value of landLineNumber
     *
     * @return ?string
     */
    public function getLandLineNumber(): ?string
    {
        return $this->landLineNumber;
    }

    /**
     * Set the value of landLineNumber
     *
     * @param ?string $landLineNumber
     *
     * @return self
     */
    public function setLandLineNumber(?string $landLineNumber): self
    {
        $this->landLineNumber = $landLineNumber;

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
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of linkedinUrl
     *
     * @return ?string
     */
    public function getLinkedinUrl(): ?string
    {
        return $this->linkedinUrl;
    }

    /**
     * Set the value of linkedinUrl
     *
     * @param ?string $linkedinUrl
     *
     * @return self
     */
    public function setLinkedinUrl(?string $linkedinUrl): self
    {
        $this->linkedinUrl = $linkedinUrl;

        return $this;
    }

    /**
     * Get the value of githubUrl
     *
     * @return ?string
     */
    public function getGithubUrl(): ?string
    {
        return $this->githubUrl;
    }

    /**
     * Set the value of githubUrl
     *
     * @param ?string $githubUrl
     *
     * @return self
     */
    public function setGithubUrl(?string $githubUrl): self
    {
        $this->githubUrl = $githubUrl;

        return $this;
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
     * Get the value of email
     *
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param ?string $email
     *
     * @return self
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
