<?php
namespace Trails\Exception;

class RoutingError extends Exception
{
    public function __construct($message)
    {
        parent::__construct(400, $message);
    }
}
