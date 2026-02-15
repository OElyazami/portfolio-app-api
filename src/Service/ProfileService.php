<?php

namespace App\Service;

use App\Dto\Profile\Input\CreateProfileDto;
use App\Dto\Profile\Input\UpdateProfileDto;
use App\Dto\Profile\Output\ProfileOutputDto;
use App\Entity\Profile;
use App\Entity\User;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ProfileService
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /**
     * Get the authenticated user's profile
     */
    public function getProfile(User $user): IResponseArrayFormat
    {
        try {
            $profile = $user->getProfile();

            if (!$profile) {
                return (new ErrorResponse())
                    ->setMessage('Profile not found. Please create your profile first.')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(ProfileOutputDto::fromEntity($profile)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Get a profile by user ID (public endpoint for viewing portfolios)
     */
    public function getProfileByUserId(int $userId): IResponseArrayFormat
    {
        try {
            $profile = $this->em->getRepository(Profile::class)
                ->findOneBy(['user' => $userId]);

            if (!$profile) {
                return (new ErrorResponse())
                    ->setMessage('Profile not found')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            return (new SuccessResponse())->setData(ProfileOutputDto::fromEntity($profile)->toArray());
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Create a new profile for the authenticated user
     */
    public function createProfile(CreateProfileDto $dto, User $user): IResponseArrayFormat
    {
        try {
            // A user can only have one profile
            if ($user->getProfile()) {
                return (new ErrorResponse())
                    ->setMessage('Profile already exists. Use update instead.')
                    ->setCode(Response::HTTP_CONFLICT);
            }

            $profile = new Profile();
            $profile->setFirstName($dto->firstName)
                    ->setLastName($dto->lastName)
                    ->setJobTitle($dto->jobTitle)
                    ->setBio($dto->bio)
                    ->setAvatarImage($dto->avatarImage)
                    ->setWebsite($dto->website)
                    ->setAddress($dto->address)
                    ->setContactEmail($dto->contactEmail)
                    ->setMobileNumber($dto->mobileNumber)
                    ->setLandLineNumber($dto->landLineNumber)
                    ->setLinkedinUrl($dto->linkedinUrl)
                    ->setGithubUrl($dto->githubUrl)
                    ->setUser($user);

            $user->setProfile($profile);

            $this->em->persist($profile);
            $this->em->flush();

            return (new SuccessResponse())
                ->setData(ProfileOutputDto::fromEntity($profile)->toArray())
                ->setCode(Response::HTTP_CREATED);
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }

    /**
     * Update the authenticated user's profile
     */
    public function updateProfile(UpdateProfileDto $dto, User $user): IResponseArrayFormat
    {
        try {
            $profile = $user->getProfile();

            if (!$profile) {
                return (new ErrorResponse())
                    ->setMessage('Profile not found. Please create your profile first.')
                    ->setCode(Response::HTTP_NOT_FOUND);
            }

            if ($dto->firstName !== null) {
                $profile->setFirstName($dto->firstName);
            }
            if ($dto->lastName !== null) {
                $profile->setLastName($dto->lastName);
            }
            if ($dto->jobTitle !== null) {
                $profile->setJobTitle($dto->jobTitle);
            }
            if ($dto->bio !== null) {
                $profile->setBio($dto->bio);
            }
            if ($dto->avatarImage !== null) {
                $profile->setAvatarImage($dto->avatarImage);
            }
            if ($dto->website !== null) {
                $profile->setWebsite($dto->website);
            }
            if ($dto->address !== null) {
                $profile->setAddress($dto->address);
            }
            if ($dto->contactEmail !== null) {
                $profile->setContactEmail($dto->contactEmail);
            }
            if ($dto->mobileNumber !== null) {
                $profile->setMobileNumber($dto->mobileNumber);
            }
            if ($dto->landLineNumber !== null) {
                $profile->setLandLineNumber($dto->landLineNumber);
            }
            if ($dto->linkedinUrl !== null) {
                $profile->setLinkedinUrl($dto->linkedinUrl);
            }
            if ($dto->githubUrl !== null) {
                $profile->setGithubUrl($dto->githubUrl);
            }

            $this->em->flush();

            return (new SuccessResponse())
                ->setData(ProfileOutputDto::fromEntity($profile)->toArray())
                ->setMessage('Profile updated successfully');
        } catch (Exception $e) {
            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
