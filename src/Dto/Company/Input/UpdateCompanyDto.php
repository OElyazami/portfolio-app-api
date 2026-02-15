<?php

namespace App\Dto\Company\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateCompanyDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Title must be at least {{ limit }} characters long',
            maxMessage: 'Title cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $title = null,

        public readonly ?string $description = null,

        #[Assert\Url(message: 'Logo image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $logoImage = null
    ) {}
}
