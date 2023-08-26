<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class Boolean extends AbstractRule
{
    public function __construct(protected ?bool $expect = null, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        if ($this->expect === null) {
            return $this->getResult(is_bool($value));
        }

        return $this->getResult($value === $this->expect);
    }
}
