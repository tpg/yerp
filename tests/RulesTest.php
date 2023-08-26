<?php

declare(strict_types=1);

use TPG\Yerp\Rules;
use TPG\Yerp\Tests\RulesTestClass;
use TPG\Yerp\Validator;
use TPG\Yerp\Tests\CustomRule;

beforeEach(function () {
    $testClass = new RulesTestClass();

    $validator = new TPG\Yerp\Validator($testClass);
    $this->validated = $validator->validate();
});

test('Boolean rule', function () {

    expect($this->validated->property('genericBoolean', Rules\Boolean::class)->passed())->toBeTrue()
        ->and($this->validated->property('booleanIsFalse', Rules\Boolean::class)->passed())->toBeTrue()
        ->and($this->validated->property('booleanIsTrue', Rules\Boolean::class)->passed())->toBeTrue();

});

test('Email rule', function () {
    expect($this->validated->property('emailTrue', Rules\Email::class)->passed())->toBeTrue()
        ->and($this->validated->property('emailFalse', Rules\Email::class)->passed())->toBeFalse();
});

test('Length rule', function () {
    expect($this->validated->property('lengthTrue', Rules\Length::class)->passed())->toBeTrue()
        ->and($this->validated->property('lengthTooLong', Rules\Length::class)->passed())->toBeFalse()
        ->and($this->validated->property('lengthTooShort', Rules\Length::class)->passed())->toBeFalse()
        ->and($this->validated->property('lengthArray', Rules\Length::class)->passed())->toBeTrue();
});

test('Alpha rule', function () {
    expect($this->validated->property('alphaTrue', Rules\Alpha::class)->passed())->toBeTrue()
        ->and($this->validated->property('alphaFalse', Rules\Alpha::class)->passed())->toBeFalse();
});

test('Numeric rule', function () {
    expect($this->validated->property('numericTrue', Rules\Numeric::class)->passed())->toBeTrue()
        ->and($this->validated->property('numericFalse', Rules\Numeric::class)->passed())->toBeFalse();
});

test('Regex rule', function () {
    expect($this->validated->property('regexTrue', Rules\Regex::class)->passed())->toBeTrue()
        ->and($this->validated->property('regexFalse', Rules\Regex::class)->passed())->toBeFalse();
});

test('Required rule', function () {

    expect($this->validated->property('requiredTrue', Rules\Required::class)->passed())->toBeTrue()
        ->and($this->validated->property('requiredFalse', Rules\Required::class)->passed())->toBeFalse();

});

test('Equality rule', function () {
    expect($this->validated->property('equalTrue', Rules\Equal::class)->passed())->toBeTrue()
        ->and($this->validated->property('equalFalse', Rules\NotEqual::class)->passed())->toBeTrue();
});

test('Custom rule', function () {
    $object = new class {
        #[CustomRule]
        public string $test = 'foo';
    };

    $validated = (new Validator($object))->validate();
    expect($validated->passed())->toBeTrue();
});
