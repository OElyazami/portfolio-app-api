<?php

namespace App\Dto\Certification\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCertificationDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 5,
            minMessage: 'Title must be at least {{ limit }} characters long'
        )]
        public readonly ?string $title = null,

        public readonly ?string $description = null
    ) {}
}
