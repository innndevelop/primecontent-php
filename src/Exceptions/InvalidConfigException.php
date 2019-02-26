<?php

namespace Primecontent\Exceptions;

class InvalidConfigException extends \Exception
{
    public function __construct($message="Invalid config", $code = 500, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
