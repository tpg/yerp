<?php

declare(strict_types=1);

namespace TPG\Yerp\Exceptions;

use Exception;
use JetBrains\PhpStorm\Pure;

class InvalidRuleException extends Exception
{
    /**
     * @param string<class-string> $rule
     */
    public function __construct(string $rule)
    {
        $message = 'No such rule class '.$rule;
        parent::__construct($message);
    }
}
