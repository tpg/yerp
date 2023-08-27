<?php

declare(strict_types=1);

namespace TPG\Yerp;

use ReflectionProperty;
use TPG\Yerp\Exceptions\InvalidProperty;
use TPG\Yerp\Exceptions\InvalidRuleException;
use TPG\Yerp\Rules\AbstractRule;

/**
 * @template TRuleClass of AbstractRule
 */
readonly class Validated
{
    /**
     * @var array|string[]
     */
    public array $propertyNames;

    /**
     * @param array<ReflectionProperty> $properties
     * @param array<string, array<class-string<TRuleClass>, bool>> $results
     */
    public function __construct(protected array $properties, protected array $results)
    {
        $this->propertyNames = array_map(fn (ReflectionProperty $property) => $property->name, $properties);
    }

    /**
     * @return array<class-string<TRuleClass>, bool>
     * @throws InvalidProperty
     */
    public function results(string $property): array
    {
        if (! array_key_exists($property, $this->results)) {
            throw new InvalidProperty($property);
        }

        return $this->results[$property];
    }

    /**
     * @param string $property
     * @param class-string<TRuleClass>|null $rule
     * @throws InvalidProperty|InvalidRuleException
     */
    public function property(string $property, string $rule = null): Result
    {
        $result = $this->results($property);

        if (! $rule) {
            $filter = array_filter($result, fn (Result $result) => $result->failed());
            $messages = array_map(fn (Result $result) => $result->message(), $result);
            return new Result(count($filter) === 0, messages: $messages);
        }

        if (! array_key_exists($rule, $result)) {
            throw new InvalidRuleException($rule);
        }

        return $result[$rule];
    }

    public function passed(): bool
    {
        $failures = array_filter($this->properties, fn (ReflectionProperty $property) => $this->property($property->name)->failed());

        return count($failures) === 0;
    }

    public function failed(): bool
    {
        return ! $this->passed();
    }

    public function messages(): array
    {
        $messages = [];
        foreach ($this->properties as $property) {
            $messages[$property->name] = array_filter(
                $this->property($property->name)->message(),
                fn ($message) => ! is_null($message)
            );
        }

        return array_filter($messages, fn ($rules) => ! empty($rules));
    }
}
