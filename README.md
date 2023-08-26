[![Run Tests](https://github.com/tpg/yerp/actions/workflows/tests.yml/badge.svg)](https://github.com/tpg/yerp/actions/workflows/tests.yml)

# Yerp

Yerp is an object property validation library for PHP. It was written to provide simply validation for some other libraries, but it has some value elsewhere.

Yerp uses PHP Attributes to set validation rules on public properties. You can then pass an instance of that object to the validator and get a result. Results are are boolean, so there's no translation or language requirements. You can specify your own messages based on the result.

## Installation

```
composer require thepublicgood/yerp
```

## Usage

Each validation rule is a different attribute class in the `TPG\Yerp\Rules` namespace.

Here's a quick example:

```php
use TPG\Yerp\Rules;

class User
{
    #[Rules\Required]
    public string $firstName;
    #[Rules\Nullable]
    public ?string $lastName = null;
    #[Rules\Required, Rules\Email]
    public string $emailAddress;
    #[Rules\Boolean(true)]
    public boolean $active;
}
```

There are a number of build in rules and you can easily add your own.

## Running the validator

To validate the values of an instance, pass it to the `Validator` class and call the `validate` method. This will return an instance of `Validated` which provides access to the results of each rule on each property.

```php
class UserController
{
    public function create()
    {
        // Create a new object instance
        $user = new User();
        $user->firstName = 'John';
        $user->lastName = 'Doe';
        $user->emailAddress = 'john.doe@example.com';
        $user->active = false;

        // Pass to the validator
        $validated = (new Validator($user))->validate();
    }
}
```

## Getting the results

The `Validated` class provides two methods you can use: `results` and `property`. The `results` method returns an array of results, and the `property` method can be used to get to the result of a particular rule. The `property` method requires the name of the property and the attribute class name. It returns an instance of `Result` which provides a simple `passed` method:

```php
$validated = (new Validator($user))->validate();

$validated->property('firstName', Rules\Required::class)->passed();
```

The `passed` method returns a boolean representing the validation result of that rule. There is also a `failed` method which returns the exact opposite;

```php
$validated->property('firstName', Rules\Required::class)->failed();
```

The `results` method requires just the name of the property and returns an array keyed by the attribute class names:

```php
$results = $validated->results('emailAddress');

/**
 * [
 *     'TPG\Yerp\Rules\Required' => true,
 *     'TPG\Yerp\Rules\Email' => true,
 * ]
 **/
```

## Stopping on the first error

All validation rules will be evaluated in the order they appear. However you can tell Yerp to stop validating the rest of the rules if a specific one fails. You can do this by passing `true` to the `last` property on any rule:

```php
#[Rule\Nullable, Rules\Email(last: true), Rule\Equal('test@example.com')]
public string $emailAddress;
```

In the example above, if the `Email` rule fails, then the `Equal` rule will not be evaluated. If the `Email` rule DOES pass, then the `Equal` rule will be evaluated and will be present in the results. If you end the evaluation by using `last`, then any rules that come later will not be included. This is important because if you try to check the outcome of a rule that doesn't exist in the results, you'll get an `InvalidRuleException`.

## Setting messages per rule

Although Yerp is designed to be translation agnostic, it can be handy to set a static message. You can do so by passing a string value to the `failure` property of any rule:

```php
#[Rules\Email(failure: 'Must be a valid email address')]
public string $email;
```

When this rule evaluates, you'll be able to use the `message` method on the `Result` class:

```php
$validated->property('email', Rules\Email::class)->message();
```

If the rule passes, or no message is set, then the `message` method will return `null`. By default, the message is only returned when the rule fails. However, you can specify a success message if you like by passing a string to the `success`. property:

```php
#[Rules\Email(
    success: 'The email address is valid!',    // Success
    failure: 'Must be a valid email address',  // Failed
)]
public string $email;
```

## Available rules

### Required

The `Required` rule is handy to validate a property that must be present. A "required" property must contain a value, cannot be null and cannot be empty. An empty array, or an empty string would fail:

```php
#[Rules\Required]
property ?string $someString;
```

### Alpha

The `Alpha` rule ensures that the value of the property contains only letters.

```php
#[Rules\Alpha]
property string $someString;
```

### ArrayKey

The `ArrayKey` rule simply ensures that the specified key exists in the array:

```php
#[Rules\ArrayKey('test')]
property array $someArray;
```

### Boolean

The `Boolean` rule allows you to validate that the property is either TRUE or FALSE:

```php
#[Rules\Boolean(true)]
property bool $mustBeTrue;
```

### Equality

You can ensure that a property is equal to a specific value. Simply pass the required value as the first parameter of the `Euqal` rule:

```php
#[Rules\Equal('expected')]
propert ?string $someString;
```

There is also a `NotEqual` rule that works in the same way by opposite.

### Email

A common validation is to ensure that a string is a valid email address:

```php
#[Rules\Email]
property string $emailAddress;
```

### In

The `In` rule allows you to test if a value is one of the specified values:

```php
#[Rules\In(['a', 'b', 'c'])]
property string $someString;
```

### Length

The `Length` rule allows you to specify a minimum and/or maximum length. If the property type is an array, then length validates the number of array elements:

```php
#[Rules\Length(min: 5, max: 20)]
property string $someString;
```

You don't need to specify both, but you must specify at least a minimum or maximum value.

### Numeric

The `Numeric` rule ensures that the property is parsable as a number:

```php
#[Rules\Numeric]
property string $someString;
```

### Regex

You can pass a regex string as the first parameter of the `Regex` rule:

```php
#[Rules\Regex('/^test$/')]
property string $regexString;
```

## Writing new rules

Yerp only provides a small number of rules. This was mainly because they're the only ones we needed. We might add new rules as we need them more often, but it's simply to add your own rules without needing to ask.

Create a new class to contain your rule logic extending the `TPG\Yerp\Rules\AbstractRule` class. Add  `#[Attribute]` to the class definition.

You'll need to implement the required `validate` method which returns a `TPG\Yerp\Result`. You can use the `getResult` method, which takes a boolean value, to return a new `Result` instance:

```php
namespace CustomRules;

use Attribute;
use TPG\Yerp\Rules\AbstractRule;
use TPG\Yerp\Result;

#[Attribute]
class HyphenatedRule extends AbstractRule
{
    public function validate(mixed $value): Result
    {
        return $this->getResult(str_contains((string)$value, '-'));
    }
}
```

Now you can use your new rule in any class:

```php

use CustomRules\Hyphenated;

class Article
{
    public string $title;
    #[HyphenatedRule]
    public string $slug;
}
```

Your new rule gets the same `last`, `success` and `failure` parameters.
