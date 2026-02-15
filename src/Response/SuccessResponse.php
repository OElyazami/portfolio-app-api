<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\Response;

class SuccessResponse implements IResponseArrayFormat
{

    private int $code = Response::HTTP_OK;

    private array|string|null $data = null;

    private ?string $message = null;

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getData(): array|string|null
    {
        return $this->data;
    }

    public function setData(array|string|null $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getArrayFormat(): array
    {
        $result = [
            'success' => true,
            'code' => $this->code,
            'content' => $this->data
        ];

        if ($this->message !== null) {
            $result['message'] = $this->message;
        }

        return $result;
    }

    public function getCode(): int {
        return $this->code;
    }
}
