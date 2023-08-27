<?php

declare(strict_types=1);

namespace TPG\Yerp;

readonly class Result implements \Stringable
{
    public function __construct(
        protected bool $outcome,
        protected ?string $success = null,
        protected ?string $failure = null,
        protected ?array $messages = null,
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

    public function message(): string|array|null
    {
        if ($this->messages) {
            return $this->messages;
        }

        return $this->passed()
            ? ($this->success ?? 'success')
            : ($this->failure ?? 'failure');
    }

    public function __toString(): string
    {
        return $this->message();
    }
}
