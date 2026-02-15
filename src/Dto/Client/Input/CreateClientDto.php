<?php

namespace App\Dto\Client\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateClientDto implements DtoInterface
{
    public function __construct(
        #[Assert\NotBlank(message: 'Client name is required')]
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Name must be at least {{ limit }} characters long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        public readonly string $name,

        #[Assert\Url(message: 'Logo image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $logoImage = null
    ) {}
}
