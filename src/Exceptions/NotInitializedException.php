<?php

namespace Primecontent\Exceptions;

class NotInitializedException extends \Exception
{
    public function __construct($message="Not initialized client", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
