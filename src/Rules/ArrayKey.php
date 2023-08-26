<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class ArrayKey extends AbstractRule
{
    public function __construct(protected mixed $key, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        return $this->getResult(array_key_exists($this->key, $value));
    }
}
