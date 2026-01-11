<?php

namespace App\Service;

use App\Dto\DtoInterface;
use App\Dto\User\Output\CurrentUserDto;
use App\Dto\User\Output\UserDetailsDto;
use App\Entity\User;

class UserService
{
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
        $userDetails = $user->getUserDetails();

        return new UserDetailsDto(
            profile: $userDetails?->getProfile(),
            mobileNumber: $userDetails?->getMobileNumber(),
            landLineNumber: $userDetails?->getLandLineNumber(),
            linkedinUrl: $userDetails?->getLinkedinUrl(),
            githubUrl: $userDetails?->getGithubUrl()
        );
    }
}
