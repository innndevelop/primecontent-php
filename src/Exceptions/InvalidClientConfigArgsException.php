<?php

namespace Primecontent\Exceptions;

class InvalidClientConfigArgsException extends \Exception
{
    public function __construct($message="Invalid client config args", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
