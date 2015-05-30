<?php
namespace Trails\Exception;

class SessionRequired extends Exception
{
    public function __construct()
    {
        $message = 'Tried to access a non existing session.';
        parent::__construct(500, $message);
    }
}
