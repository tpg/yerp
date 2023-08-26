<?php

declare(strict_types=1);

namespace TPG\Yerp;

readonly class Result implements \Stringable
{
    public function __construct(
        protected bool $outcome,
        protected ?string $success = null,
        protected ?string $failure = null
    ) {
    }

    public function passed(): bool
    {
        return $this->outcome === true;
    }

    public function failed(): bool
    {
        return $this->outcome !== true;
    }

    public function message(): ?string
    {
        return $this->passed() ? $this->success : $this->failure;
    }

    public function __toString(): string
    {
        return $this->message();
    }
}
