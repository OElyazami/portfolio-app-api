<?php

namespace App\Helper;

class OperationResult
{
    public function __construct(
        public readonly bool $success,
        public readonly ?object $data = null,
        public readonly ?string $error = null,
        public readonly ?string $errorCode = null
    ) {}
}