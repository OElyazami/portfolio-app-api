<?php

namespace App\Service;

use App\Dto\DtoInterface;
use App\Dto\User\Input\UpdateUserDto;
use App\Dto\User\Output\CurrentUserDto;
use App\Dto\User\Output\UserDetailsDto;
use App\Entity\User;
use App\Entity\UserDetails;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserService
{
    public function __construct(private EntityManagerInterface $em)
    {
    }
    public function getCurrentUser(User $user): DtoInterface
    {
        $currentUserDto = new CurrentUserDto(
            email: $user->getEmail(),
            firstName: $user?->getFirstName(),
            lastName: $user?->getLastName(),
            avatarImage: ''
        );
        return $currentUserDto;
    }

    public function getUserDetails(User $user): DtoInterface
    {
        return new UserDetailsDto(
            profile: $user->getUserDetails()?->getProfile(),
            mobileNumber: $user->getUserDetails()?->getMobileNumber(),
            landLineNumber: $user->getUserDetails()?->getLandLineNumber(),
            linkedinUrl: $user->getUserDetails()?->getLinkedinUrl(),
            githubUrl: $user->getUserDetails()?->getGithubUrl(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            address: $user->getUserDetails()?->getAddress(),
            email: $user->getUserDetails()?->getEmail()
        );
    }

    public function updateUser(UpdateUserDto $dto, User $user): User
    {
        try {
            $user->setFirstName($dto->firstName)
                 ->setLastName($dto->lastName);
            $userDetails = $user->getUserDetails() ?? new UserDetails();
            $userDetails->setAddress($dto->address)
                        ->setEmail($dto->email)
                        ->setGithubUrl($dto->githubUrl)
                        ->setLinkedinUrl($dto->linkedinUrl)
                        ->setMobileNumber($dto->mobileNumber)
                        ->setLandLineNumber($dto->landLineNumber)
                        ->setProfile($dto->profile);     
            $user->setUserDetails($userDetails);

            $this->em->flush();
        }catch (Exception $e){

        }

        return $user;
    }
}
