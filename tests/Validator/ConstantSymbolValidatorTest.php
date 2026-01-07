<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\ConstantSymbolValidator;
use PHPUnit\Framework\TestCase;

class ConstantSymbolValidatorTest extends TestCase
{
    public function testValidNumberFormat(): void
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_NONE, $validator->validate('0001'));
    }

    public function testValidNumberFormatWithSpaces(): void
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_NONE, $validator->validate(' 0001  '));
    }

    public function testInvalidNumberFormat(): void
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_FORMAT, $validator->validate('f00012'));
    }
}
