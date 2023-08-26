<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class NotEqual extends AbstractRule
{
    public function __construct(protected mixed $expect, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        return $this->getResult($value !== $this->expect);
    }
}
