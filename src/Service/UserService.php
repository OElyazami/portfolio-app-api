<?php

namespace App\Service;

use App\Dto\User\Input\UpdateUserDto;
use App\Dto\User\Output\CurrentUserDto;
use App\Dto\User\Output\UserDetailsDto;
use App\Entity\User;
use App\Entity\UserDetails;
use App\Response\ErrorResponse;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function getCurrentUser(User $user): IResponseArrayFormat
    {
        return (new SuccessResponse())->setData(CurrentUserDto::fromEntity($user)->toArray());
    }

    public function getUserDetails(User $user): IResponseArrayFormat
    {
        return (new SuccessResponse())->setData(UserDetailsDto::fromEntity($user)->toArray());
    }

    public function updateUser(UpdateUserDto $dto, User $user): IResponseArrayFormat
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

            if (!$user->getUserDetails()) {
                $userDetails->setUser($user);
                $user->setUserDetails($userDetails);
                $this->em->persist($userDetails);
            }
            $this->em->flush();
            return (new SuccessResponse())->setData(UserDetailsDto::fromEntity($user)->toArray());
        } catch (Exception $e) {

            return (new ErrorResponse())->setMessage($e->getMessage());
        }
    }
}
