<?php

declare(strict_types=1);

namespace TPG\Yerp\Tests;

use TPG\Yerp\Rules;

class RulesTestClass
{
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
    #[Rules\Length(max: 2)]
    public array $lengthArray = [
        'a',
        'b',
    ];

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
}
