<?php

namespace App\Dto\User\Input;

class UpdateUserDto {

    public function __construct(
        private readonly string $firstName,
        private readonly string $lastName,
        private readonly string $profile,
        private readonly string $mobileNumber,
        private readonly string $landLineNumber,
        private readonly string $linkdinUrl,
        private readonly string $githubUrl
    )
    {}
}
