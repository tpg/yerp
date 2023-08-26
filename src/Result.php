<?php

declare(strict_types=1);

namespace TPG\Yerp;

readonly class Result implements \Stringable
{
    public function __construct(protected string|bool $outcome)
    {
    }

    public function passed(): bool
    {
        return $this->outcome === true;
    }

    public function failed(): bool
    {
        return $this->outcome === false;
    }

    public function message(): string
    {
        return (string)$this->outcome;
    }

    public function __toString(): string
    {
        return $this->message();
    }
}
