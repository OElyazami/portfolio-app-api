<?php

namespace App\Dto\Client\Input;

use App\Dto\DtoInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateClientDto implements DtoInterface
{
    public function __construct(
        #[Assert\Length(
            min: 2,
            max: 25,
            minMessage: 'Name must be at least {{ limit }} characters long',
            maxMessage: 'Name cannot be longer than {{ limit }} characters'
        )]
        public readonly ?string $name = null,

        #[Assert\Url(message: 'Logo image must be a valid URL')]
        #[Assert\Length(max: 255)]
        public readonly ?string $logoImage = null
    ) {}
}
