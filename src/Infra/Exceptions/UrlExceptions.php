<?php

namespace API\CheckPrice\Infra\Exceptions;

use Exception;

class UrlException extends Exception
{
    const ERROR_URL_NOT_FOUND = 'URL_NOT_FOUND';
    const ERROR_INVALID_URL = 'INVALID_URL';
    const ERROR_URL_GENERATION_FAILED = 'URL_GENERATION_FAILED';

    public function __construct(string $url, string $errorType, string $message = '')
    {
        $message = $message ?: "Erro relacionado à URL: {$url}. Tipo de erro: {$errorType}.";

        parent::__construct($message);
    }

}
