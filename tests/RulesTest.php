<?php

declare(strict_types=1);

use TPG\Yerp\Rules;

beforeEach(function () {
    $testClass = new class () {
        #[Rules\ArrayKey(key: 'test')]
        public array $arrayKeyTrue = ['test' => 'example'];
        #[Rules\ArrayKey(key: 'test')]
        public array $arrayKeyFalse = ['not-a-test' => 'example'];

        #[Rules\Boolean]
        public mixed $genericBoolean = true;
        #[Rules\Boolean(false)]
        public mixed $booleanIsFalse = false;
        #[Rules\Boolean(true)]
        public mixed $booleanIsTrue = true;

        #[Rules\Email]
        public string $emailTrue = 'test@example.com';
        #[Rules\Email]
        public string $emailFalse = '@not-an-email';

        #[Rules\Equal('test')]
        public string $equalTrue = 'test';

        #[Rules\NotEqual('test')]
        public string $equalFalse = 'not-test';

        #[Rules\In(['test', 'example'])]
        public array $inTrue = ['test'];
        #[Rules\In(['test', 'example'])]
        public array $inFalse = ['bad'];

        #[Rules\Length(max: 10)]
        public string $lengthTrue = 'test-max';

        #[Rules\Length(max: 5)]
        public string $lengthTooLong = 'test-length';

        #[Rules\Length(min: 6)]
        public string $lengthTooShort = 'test';

        #[Rules\Alpha]
        public string $alphaTrue = 'test';
        #[Rules\Alpha]
        public string $alphaFalse = 'test123';

        #[Rules\Numeric]
        public string $numericTrue = '55.6';
        #[Rules\Numeric]
        public string $numericFalse = 'not-a-number';

        #[Rules\Regex('/^test$/')]
        public string $regexTrue = 'test';
        #[Rules\Regex('/^test$/')]
        public string $regexFalse = 'testing';

        #[Rules\Required]
        public string $requiredTrue = 'John Doe';
        #[Rules\Required]
        public string $requiredFalse = '';
    };

    $validator = new TPG\Yerp\Validator($testClass);
    $this->validated = $validator->validate();
});

test('Boolean rules', function () {

    expect($this->validated->property('genericBoolean', Rules\Boolean::class)->passed())->toBeTrue()
        ->and($this->validated->property('booleanIsFalse', Rules\Boolean::class)->passed())->toBeTrue()
        ->and($this->validated->property('booleanIsTrue', Rules\Boolean::class)->passed())->toBeTrue();

});

test('Email rules', function () {
    expect($this->validated->property('emailTrue', Rules\Email::class)->passed())->toBeTrue()
        ->and($this->validated->property('emailFalse', Rules\Email::class)->passed())->toBeFalse();
});

test('Length rules', function () {
    expect($this->validated->property('lengthTrue', Rules\Length::class)->passed())->toBeTrue()
        ->and($this->validated->property('lengthTooLong', Rules\Length::class)->passed())->toBeFalse()
        ->and($this->validated->property('lengthTooShort', Rules\Length::class)->passed())->toBeFalse();
});

test('Alpha rules', function () {
    expect($this->validated->property('alphaTrue', Rules\Alpha::class)->passed())->toBeTrue()
        ->and($this->validated->property('alphaFalse', Rules\Alpha::class)->passed())->toBeFalse();
});

test('Numeric rules', function () {
    expect($this->validated->property('numericTrue', Rules\Numeric::class)->passed())->toBeTrue()
        ->and($this->validated->property('numericFalse', Rules\Numeric::class)->passed())->toBeFalse();
});

test('Regex rules', function () {
    expect($this->validated->property('regexTrue', Rules\Regex::class)->passed())->toBeTrue()
        ->and($this->validated->property('regexFalse', Rules\Regex::class)->passed())->toBeFalse();
});

test('Required rules', function () {

    expect($this->validated->property('requiredTrue', Rules\Required::class)->passed())->toBeTrue()
        ->and($this->validated->property('requiredFalse', Rules\Required::class)->passed())->toBeFalse();

});

test('Equality rules', function () {
    expect($this->validated->property('equalTrue', Rules\Equal::class)->passed())->toBeTrue()
        ->and($this->validated->property('equalFalse', Rules\NotEqual::class)->passed())->toBeTrue();
});
