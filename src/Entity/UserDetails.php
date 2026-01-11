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
    private int $id;
    
    #[ORM\Column(type: 'text', nullable: true, length: 255)]
    #[Assert\Length(
        max: 255
    )]
    private ?string $address;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $profile;

    #[ORM\Column(nullable: true)]
    private ?string $mobileNumber;


    #[ORM\Column(nullable: true)]
    private ?string $landLineNumber;
    
    #[ORM\OneToOne(inversedBy: 'userDetails', targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', unique: true, nullable: false)]
    private User $user;

    #[ORM\Column(nullable: true)]
    private ?string $linkedinUrl;

    #[ORM\Column(nullable: true)]
    private ?string $githubUrl;

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
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
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
}
