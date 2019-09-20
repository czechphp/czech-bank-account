<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\SpecificSymbolValidator;
use PHPUnit\Framework\TestCase;

class SpecificSymbolValidatorTest extends TestCase
{
    /**
     * @dataProvider validProvider
     *
     * @param string $value
     */
    public function testValid(string $value)
    {
        $validator = new SpecificSymbolValidator();

        $this->assertSame(SpecificSymbolValidator::ERROR_NONE, $validator->validate($value));
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
        $validator = new SpecificSymbolValidator();

        $this->assertSame(SpecificSymbolValidator::ERROR_FORMAT, $validator->validate('12345678901'));
    }

    public function testInvalidCharacter()
    {
        $validator = new SpecificSymbolValidator();

        $this->assertSame(SpecificSymbolValidator::ERROR_FORMAT, $validator->validate('foo'));
    }
}
