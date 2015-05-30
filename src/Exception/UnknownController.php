<?php
namespace Trails\Exception;

class UnknownController extends Exception
{
    public function __construct($message)
    {
        parent::__construct(404, $message);
    }
}
