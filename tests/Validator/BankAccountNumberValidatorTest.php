<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Validator\BankAccountNumberValidator;
use PHPUnit\Framework\TestCase;

class BankAccountNumberValidatorTest extends TestCase
{
    public function testInvalidTypeOption(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_NONE,
            $validator->validate('1', ['type' => 'foo'])
        );
    }

    /**
     * @dataProvider validProvider
     */
    public function testValid(string $value, array $options = []): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_NONE,
            $validator->validate($value, $options)
        );
    }

    public function validProvider(): array
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

    public function testVariableTypeTooShortNumber(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_VARIABLE,
            $validator->validate('1', ['type' => BankAccountNumberValidator::OPTION_TYPE_VARIABLE])
        );
    }

    public function testConstantTypeTooShortNumber(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_CONSTANT,
            $validator->validate('1', ['type' => BankAccountNumberValidator::OPTION_TYPE_CONSTANT])
        );
    }

    public function testVariableTypeTooLongNumber(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_VARIABLE,
            $validator->validate('12345678901', ['type' => BankAccountNumberValidator::OPTION_TYPE_VARIABLE])
        );
    }

    public function testConstantTypeTooLongNumber(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_CONSTANT,
            $validator->validate('12345678901234567', ['type' => BankAccountNumberValidator::OPTION_TYPE_CONSTANT])
        );
    }

    public function testFirstPartInvalidChecksum(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FIRST_PART_CHECKSUM,
            $validator->validate('11-19')
        );
    }

    public function testFirstPartNoDigits(): void
    {
        $validator = new BankAccountNumberValidator();

        $this->assertSame(
            BankAccountNumberValidator::ERROR_FORMAT_VARIABLE,
            $validator->validate('-19')
        );
    }

    public function testSecondPartInvalidChecksum(): void
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
