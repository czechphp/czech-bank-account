<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\VariableSymbolValidator;
use PHPUnit\Framework\TestCase;

class VariableSymbolValidatorTest extends TestCase
{
    /**
     * @dataProvider validProvider
     */
    public function testValid(string $value): void
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_NONE, $validator->validate($value));
    }

    public function validProvider(): array
    {
        return [
            [''],
            ['0'],
            ['1'],
            ['123456'],
            ['1234567890'],
        ];
    }

    public function testTooLong(): void
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_FORMAT, $validator->validate('12345678901'));
    }

    public function testInvalidCharacter(): void
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_FORMAT, $validator->validate('foo'));
    }
}
