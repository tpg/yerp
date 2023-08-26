<?php

declare(strict_types=1);

namespace TPG\Yerp;

use ReflectionClass;
use ReflectionProperty;
use TPG\Yerp\Contracts\ValidatorInterface;
use TPG\Yerp\Rules\AbstractRule;

class Validator implements ValidatorInterface
{
    public array $errors;

    public function __construct(protected mixed $source)
    {
    }

    public function validate(): Validated
    {
        $properties = (new ReflectionClass($this->source))->getProperties(ReflectionProperty::IS_PUBLIC);
        $errors = [];

        foreach ($properties as $property) {
            $errors[$property->getName()] = $this->validateProperty($property);
        }

        return new Validated($properties, $errors);
    }

    protected function validateProperty(ReflectionProperty $property): array
    {
        $attributes = $property->getAttributes();

        $errors = [];

        foreach ($attributes as $attribute) {
            $rule = $attribute->newInstance();

            assert($rule instanceof AbstractRule);

            $errors[$attribute->getName()] = $success = $rule->validate($property->getValue($this->source));

            if ($rule->last && $success->failed()) {
                break;
            }
        }

        return $errors;
    }
}
