<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

abstract class AbstractRule
{

    public function __construct(
        public readonly bool $last = false,
        public readonly ?string $message = null,
    )
    {
    }

    abstract public function validate(mixed $value): Result;

    protected function getResult(bool $passed): Result
    {
        return new Result($passed ?: $this->fail());
    }

    protected function fail(): false|string
    {
        return $this->message ?: false;
    }
}
