<?php

declare(strict_types=1);

namespace TPG\Yerp\Tests;

use Attribute;
use TPG\Yerp\Result;
use TPG\Yerp\Rules\AbstractRule;

#[Attribute]
class CustomRule extends AbstractRule
{
    public function validate(mixed $value): Result
    {
        return $this->getResult($value === 'foo');
    }
}
