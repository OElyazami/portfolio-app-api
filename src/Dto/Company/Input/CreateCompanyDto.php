<?php

namespace App\Dto\Company\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateCompanyDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Company title is required')]
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Title must be at least {{ limit }} characters long',
            maxMessage: 'Title cannot be longer than {{ limit }} characters'
        )]
        public readonly string $title,

        #[Assert\NotBlank(message: 'Description is required')]
        public readonly string $description,

        #[Assert\Url(message: 'Logo image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $logoImage = null
    ) {}
}
