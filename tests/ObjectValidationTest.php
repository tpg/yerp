<?php

declare(strict_types=1);

use TPG\Yerp\Rules;
use TPG\Yerp\Validator;

it('can validate object properties', function () {

    $object = new class {
        #[Rules\Required, Rules\StringType]
        public string $name = 'John Doe';
        #[Rules\Required, Rules\StringType, Rules\Email]
        public string $email = 'bad-email';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->property('name', Rules\Required::class)->passed())->toBeTrue()
        ->and($validated->property('email', Rules\Required::class)->passed())->toBeTrue()
        ->and($validated->property('email', Rules\Required::class)->failed())->toBeFalse()
        ->and($validated->property('email', Rules\Email::class)->passed())->toBeFalse();
});

it('can have custom validation messages', function () {

    $object = new class {
        #[Rules\Required(message: 'The name field is required')]
        public string $name = '';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->property('name', Rules\Required::class)->message())
        ->toBe('The name field is required')
        ->and((string)$validated->property('name', Rules\Required::class))->toEqual('The name field is required');
});

it('can stop of a rule marked as last', function () {

    $object = new class {
        #[Rules\Required, Rules\StringType(last: true), Rules\Email]
        public string $email = 'email@example.test';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->results('email'))->toHaveKey(Rules\StringType::class)
        ->and($validated->results('email'))->not->toHaveKey(Rules\Email::class);
});

test('it will throw an exception if a property doesn\'t exist', function () {

    $object = new class {
        #[Rules\Required]
        public string $name = 'John Doe';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    $result = $validated->results('email');

})->throws(\TPG\Yerp\Exceptions\InvalidProperty::class);

test('it will throw an exception if a rule hasn\'t been applied.', function () {

    $object = new class {
        #[Rules\Required]
        public string $email = 'text@example.com';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    $result = $validated->property('email', Rules\Email::class);

})->throws(\TPG\Yerp\Exceptions\InvalidRuleException::class);
