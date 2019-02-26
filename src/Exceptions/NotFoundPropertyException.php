<?php

namespace Primecontent\Exceptions;

class NotFoundPropertyException extends \Exception
{
    public function __construct($message="Not found client property", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
