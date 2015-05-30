<?php

namespace Trails\Exception;

class MissingFile extends Exception
{
    public function __construct($message)
    {
        parent::__construct(500, $message);
    }
}
