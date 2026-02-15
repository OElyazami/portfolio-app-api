<?php

namespace App\Service;

use App\Dto\User\Output\CurrentUserDto;
use App\Entity\User;
use App\Response\IResponseArrayFormat;
use App\Response\SuccessResponse;

class UserService
{
    /**
     * Get current authenticated user info (auth-only data)
     */
    public function getCurrentUser(User $user): IResponseArrayFormat
    {
        return (new SuccessResponse())->setData(CurrentUserDto::fromEntity($user)->toArray());
    }
}
