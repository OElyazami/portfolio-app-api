<?php

namespace App\Dto;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseInputDto {


    public function validate(): array
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($this);

        $errors = [];
        foreach ($violations as $violation){
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

    public function toArray():array
    {
        return [];
    }
}