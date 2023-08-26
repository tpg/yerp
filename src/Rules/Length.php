<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class Length extends AbstractRule
{
    public function __construct(protected ?int $min = null, protected ?int $max = null, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        if (! $this->min && ! $this->max) {
            throw new \RuntimeException('A minimum or maximum length must be specified.');
        }

        $length = match(is_array($value)) {
            true => count($value),
            false => strlen((string)$value),
        };

        $min = !$this->min || $length >= $this->min;
        $max = !$this->max || $length <= $this->max;

        return $this->getResult($min && $max);
    }
}
