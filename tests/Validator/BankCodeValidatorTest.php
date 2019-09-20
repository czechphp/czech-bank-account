<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;
use Czechphp\CzechBankAccount\Validator\BankCodeValidator;
use Czechphp\CzechBankAccount\Validator\SpecificSymbolValidator;
use PHPUnit\Framework\TestCase;

class BankCodeValidatorTest extends TestCase
{
    public function testValid()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn([
            [
                LoaderInterface::CODE => '0001',
            ],
            [
                LoaderInterface::CODE => '1000',
            ],
        ]);

        $validator = new BankCodeValidator($loader);

        $this->assertSame(SpecificSymbolValidator::ERROR_NONE, $validator->validate('0001'));
    }

    public function testNonExistentCode()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn([
            [
                LoaderInterface::CODE => '0001',
            ],
        ]);

        $validator = new BankCodeValidator($loader);

        $this->assertSame(BankCodeValidator::ERROR_INVALID_CODE, $validator->validate('0002'));
    }

    public function testTooLong()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->never())->method('load');

        $validator = new BankCodeValidator($loader);

        $this->assertSame(BankCodeValidator::ERROR_FORMAT, $validator->validate('12345678901'));
    }

    public function testInvalidCharacter()
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->never())->method('load');

        $validator = new BankCodeValidator($loader);

        $this->assertSame(BankCodeValidator::ERROR_FORMAT, $validator->validate('foo'));
    }
}
