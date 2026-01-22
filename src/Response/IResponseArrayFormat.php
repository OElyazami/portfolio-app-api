<?php

namespace App\Response;

interface IResponseArrayFormat{
    public function getArrayFormat(): array;

    public function getCode(): int;
}