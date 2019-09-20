<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\VariableSymbolValidator;
use PHPUnit\Framework\TestCase;

class VariableSymbolValidatorTest extends TestCase
{
    /**
     * @dataProvider validProvider
     *
     * @param string $value
     */
    public function testValid(string $value)
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_NONE, $validator->validate($value));
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            [''],
            ['0'],
            ['1'],
            ['123456'],
            ['1234567890'],
        ];
    }

    public function testTooLong()
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_FORMAT, $validator->validate('12345678901'));
    }

    public function testInvalidCharacter()
    {
        $validator = new VariableSymbolValidator();

        $this->assertSame(VariableSymbolValidator::ERROR_FORMAT, $validator->validate('foo'));
    }
}
