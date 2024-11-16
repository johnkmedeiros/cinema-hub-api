<?php

namespace App\Application\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected string $errorCode;

    public function __construct(string $message, int $statusCode, string $errorCode)
    {
        parent::__construct($message, $statusCode);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}
