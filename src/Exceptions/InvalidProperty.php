<?php

declare(strict_types=1);

namespace TPG\Yerp\Exceptions;

use Exception;

class InvalidProperty extends Exception
{

    /**
     * @param string $property
     */
    public function __construct(string $property)
    {
        $message = 'No such property '.$property;
        parent::__construct($message);
    }
}
