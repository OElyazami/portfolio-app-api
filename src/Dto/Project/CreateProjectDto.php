<?php

namespace App\Dto\Project;

use Symfony\Component\Validator\Constraints as Assert;


class CreateProjectDto {
    
    public function __construct(
        #[Assert\Length(
            min: 5,
            max: 100
        )]
        #[Assert\NotBlank]
        private string $title,
    
        #[Assert\Length(
            max: 50
        )]
        private ?string $slug,
        
        private ?string $Description
    ){

    }

}