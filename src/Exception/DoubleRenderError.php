<?php

namespace Trails\Exception;

class DoubleRenderError extends Exception
{
    public function __construct()
    {
        $message = 'Render and/or redirect were called multiple times in this action. ' .
                   'Please note that you may only call render OR redirect, and at most ' .
                   'once per action.';

        parent::__construct(500, $message);
    }
}
