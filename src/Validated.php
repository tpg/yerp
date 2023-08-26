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
     * @param array<ReflectionProperty> $properties
     * @param array<string, <class-string<TRuleClass>, bool>> $results
     */
    public function __construct(protected array $properties, protected array $results)
    {
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
     * @param string<class-string> $rule
     * @throws InvalidRuleException|InvalidProperty
     */
    public function property(string $property, string $rule): Result
    {
        $result = $this->results($property);

        if (! array_key_exists($rule, $result)) {
            throw new InvalidRuleException($rule);
        }

        return $result[$rule];
    }
}
