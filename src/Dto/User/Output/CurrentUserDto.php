<?php

namespace App\Dto\User\Output;

use App\Dto\DtoInterface;

class CurrentUserDto implements DtoInterface {

    public function __construct(
        public readonly string $email,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly ?string $avatarImage
    )
    {}
}