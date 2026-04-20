<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    private string $errorCode;

    public function __construct(string $message = 'Erro de API.', int $status = 400, string $errorCode = 'api_error', ?Throwable $previous = null)
    {
        parent::__construct($message, $status, $previous);

        $this->errorCode = $errorCode;
    }

    public function status(): int
    {
        return $this->getCode() ?: 400;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }
}
