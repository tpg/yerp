<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class Email extends AbstractRule
{
    public function validate(mixed $value): Result
    {
        if (! is_string($value)) {
            return $this->getResult(false);
        }

        return $this->getResult(filter_var((string)$value, FILTER_VALIDATE_EMAIL) !== false);
    }
}
