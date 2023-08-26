<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class In extends AbstractRule
{
    public function __construct(protected array $values, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        if (!is_array($value)) {
            return $this->getResult(false);
        }

        return $this->getResult(in_array($value, $this->values, true));
    }
}
