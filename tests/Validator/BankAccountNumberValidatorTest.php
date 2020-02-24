<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\BankAccountNumberValidator;
use PHPUnit\Framework\TestCase;

class BankAccountNumberValidatorTest extends TestCase
{
    public function testInvalidTypeOption()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_NONE,
            $validator->validate('1', ['type' => 'foo'])
        );
    }

    /**
     * @dataProvider validProvider
     *
     * @param string $value
     * @param array $options
     */
    public function testValid(string $value, array $options = [])
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_NONE,
            $validator->validate($value, $options)
        );
    }

    /**
     * @return array
     */
    public function validProvider()
    {
        return [
            // smallest possible number
            ['19'],
            ['19-19'],
            ['0000000000000019', ['type' => 'constant']],
            ['0000190000000019', ['type' => 'constant']],
            // random valid numbers
            ['227104082'],
            ['19-2000145399'],
            ['178124-4159'],
            ['318-203'],
        ];
    }

    public function testVariableTypeTooShortNumber()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_VARIABLE,
            $validator->validate('1', ['type' => BankAccountNumberValidator::OPTION_TYPE_VARIABLE])
        );
    }

    public function testConstantTypeTooShortNumber()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_CONSTANT,
            $validator->validate('1', ['type' => BankAccountNumberValidator::OPTION_TYPE_CONSTANT])
        );
    }

    public function testVariableTypeTooLongNumber()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_VARIABLE,
            $validator->validate('12345678901', ['type' => BankAccountNumberValidator::OPTION_TYPE_VARIABLE])
        );
    }

    public function testConstantTypeTooLongNumber()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_CONSTANT,
            $validator->validate('12345678901234567', ['type' => BankAccountNumberValidator::OPTION_TYPE_CONSTANT])
        );
    }

    public function testFirstPartInvalidChecksum()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FIRST_PART_CHECKSUM,
            $validator->validate('11-19')
        );
    }

    public function testSecondPartInvalidChecksum()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_SECOND_PART_CHECKSUM,
            $validator->validate('11')
        );
    }

    public function testLessThanTwoNonZeroDigits()
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_AMOUNT_OF_NON_ZERO_DIGITS,
            $validator->validate('10')
        );
    }
}
