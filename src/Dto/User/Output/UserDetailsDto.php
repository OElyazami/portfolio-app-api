<?php

namespace App\Dto\User\Output;

use App\Dto\DtoInterface;

class UserDetailsDto implements DtoInterface {

    public function __construct(
        public readonly ?string $profile,
        public readonly ?string $mobileNumber,
        public readonly ?string $landLineNumber,
        public readonly ?string $linkedinUrl,
        public readonly ?string $githubUrl
    )
    {}
}