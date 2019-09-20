<?php

namespace Czechphp\CzechBankAccount\Tests\Utils;

use Czechphp\CzechBankAccount\Utils\BankAccountNumberFormatConverter;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BankAccountNumberFormatConverterTest extends TestCase
{
    /**
     * @dataProvider convertVariableToConstantProvider
     *
     * @param string $expected
     * @param string $value
     */
    public function testConvertVariableToConstant(string $expected, string $value)
    {
        $this->assertSame($expected, BankAccountNumberFormatConverter::convertVariableToConstant($value));
    }

    public function convertVariableToConstantProvider(): array
    {
        return [
            ['0000000000000000', ''],
            ['0000000000000019', '19'],
            ['0000190000000019', '19-19'],
            ['0000190000000019', '19 19'],
            ['0000190000000019', '19    19'],
            ['0000000000000019', '  19   '],
            ['0000190000000019', '  19-19  '],
            ['0000190000000019', '  19 19  '],
            ['0000190000000019', '  19   19  '],
        ];
    }

    public function testConvertVariableToConstantThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);

        BankAccountNumberFormatConverter::convertVariableToConstant('not a bank account number');
    }

    /**
     * @dataProvider convertConstantToVariableProvider
     *
     * @param string $expected
     * @param string $value
     */
    public function testConvertConstantToVariable(string $expected, string $value)
    {
        $this->assertSame($expected, BankAccountNumberFormatConverter::convertConstantToVariable($value));
    }

    public function convertConstantToVariableProvider(): array
    {
        return [
            ['', '0000000000000000'],
            ['19', '0000000000000019'],
            ['19-19', '0000190000000019'],
            ['19', '   0000000000000019   '],
            ['19-19', '   0000190000000019  '],
        ];
    }

    public function testConvertConstantToVariableThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);

        BankAccountNumberFormatConverter::convertConstantToVariable('not a bank account number');
    }
}
