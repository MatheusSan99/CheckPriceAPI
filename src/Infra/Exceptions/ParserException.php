<?php

namespace API\CheckPrice\Infra\Exceptions;

use Exception;

class ParserException extends Exception
{
    const ERROR_PARSER_FAILED = 'PARSER_FAILED';
    const ERROR_INVALID_JSON = 'INVALID_JSON';

    public function __construct(string $message = '', int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
