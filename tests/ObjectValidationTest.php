<?php

declare(strict_types=1);

use TPG\Yerp\Rules;
use TPG\Yerp\Validator;

it('can validate object properties', function () {

    $object = new class () {
        #[Rules\Required]
        public string $name = 'John Doe';
        #[Rules\Required, Rules\Email]
        public string $email = 'bad-email';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->property('name', Rules\Required::class)->passed())->toBeTrue()
        ->and($validated->property('email', Rules\Required::class)->passed())->toBeTrue()
        ->and($validated->property('email', Rules\Required::class)->failed())->toBeFalse()
        ->and($validated->property('email', Rules\Email::class)->passed())->toBeFalse();
});

it('can validate an entire property', function () {
    $object = new class {
        #[Rules\Equal('test@example.com'), Rules\Email]
        public string $email = 'test@example.com';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();
    expect($validated->property('email')->passed())->toBeTrue();
});

it('can validate the entire object', function  () {
    $object = new class {
        #[Rules\Required]
        public string $firstName = 'John';
        #[Rules\Required]
        public string $lastName = 'Doe';
        #[Rules\Required, Rules\Email]
        public string $emailAddress = 'bad-email';
    };

    $validated = (new Validator($object))->validate();

    expect($validated->passed())->toBeFalse()
        ->and($validated->failed())->toBeTrue();
});

it('can have custom validation messages', function () {

    $object = new class () {
        #[Rules\Required(
            success: 'success message',
            failure: 'failure message',
        )]
        public string $name = '';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->property('name', Rules\Required::class)->message())
        ->toBe('failure message')
        ->and((string)$validated->property('name', Rules\Required::class))->toEqual('failure message');

    $object->name = 'Someone';

    $validated = $validator->validate();

    expect($validated->property('name', Rules\Required::class)->message())
        ->toBe('success message')
        ->and((string)$validated->property('name', Rules\Required::class))->toEqual('success message');
});

it('can stop on a failed rule marked as last', function () {

    $object = new class () {
        #[Rules\Required, Rules\Equal('email@different.test', last: true), Rules\Email]
        public string $email = 'email@example.test';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    expect($validated->results('email'))->toHaveKey(Rules\Equal::class)
        ->and($validated->results('email'))->not->toHaveKey(Rules\Email::class);
});

test('it will throw an exception if a property doesn\'t exist', function () {

    $object = new class () {
        #[Rules\Required]
        public string $name = 'John Doe';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    $result = $validated->results('email');

})->throws(\TPG\Yerp\Exceptions\InvalidProperty::class);

test('it will throw an exception if a rule hasn\'t been applied.', function () {

    $object = new class () {
        #[Rules\Required]
        public string $email = 'text@example.com';
    };

    $validator = new Validator($object);
    $validated = $validator->validate();

    $result = $validated->property('email', Rules\Email::class);

})->throws(\TPG\Yerp\Exceptions\InvalidRuleException::class);
