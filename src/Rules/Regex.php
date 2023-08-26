<?php

declare(strict_types=1);

namespace TPG\Yerp\Rules;

use Attribute;
use TPG\Yerp\Result;

#[Attribute]
class Regex extends AbstractRule
{
    public function __construct(protected string $regex, bool $last = false, ?string $message = null)
    {
        parent::__construct($last, $message);
    }

    public function validate(mixed $value): Result
    {
        $test = preg_match($this->regex, (string)$value, $matched);

        return $this->getResult($test !== false && $test > 0);
    }
}
