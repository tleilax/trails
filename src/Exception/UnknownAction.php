<?php
namespace Trails\Exception;

class UnknownAction extends Exception
{
    public function __construct($message)
    {
        parent::__construct(404, $message);
    }
}
