<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class StringType extends AbstractRule
{
    public function validate(mixed $value): Result
    {
        return $this->getResult(is_string($value));
    }
}
