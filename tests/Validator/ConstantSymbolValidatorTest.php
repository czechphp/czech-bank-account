<?php

namespace Czechphp\CzechBankAccount\Tests\Validator;

use Czechphp\CzechBankAccount\ConstantSymbol\Filter\FilterInterface;
use Czechphp\CzechBankAccount\Validator\ConstantSymbolValidator;
use PHPUnit\Framework\TestCase;

class ConstantSymbolValidatorTest extends TestCase
{
    public function testValidNumberFormat()
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_NONE, $validator->validate('0001'));
    }

    public function testValidNumberFormatWithSpaces()
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_NONE, $validator->validate(' 0001  '));
    }

    public function testInvalidNumberFormat()
    {
        $validator = new ConstantSymbolValidator();

        $this->assertSame(ConstantSymbolValidator::ERROR_FORMAT, $validator->validate('f00012'));
    }

    public function testFilterIsNotUsedWhenNotSet()
    {
        $filter = $this->createMock(FilterInterface::class);

        $validator = new ConstantSymbolValidator($filter);

        $this->assertSame(
            ConstantSymbolValidator::ERROR_NONE,
            $validator->validate('0001', [])
        );
    }

    public function testFilterIsNotUsedWhenNull()
    {
        $filter = $this->createMock(FilterInterface::class);

        $validator = new ConstantSymbolValidator($filter);

        $this->assertSame(
            ConstantSymbolValidator::ERROR_NONE,
            $validator->validate('0001', ['filter' => null])
        );
    }

    public function testFilterIsUsed()
    {
        $filter = $this->createMock(FilterInterface::class);

        $validator = new ConstantSymbolValidator($filter);

        $this->assertSame(
            ConstantSymbolValidator::ERROR_INVALID_CODE,
            $validator->validate('0001', ['filter' => []])
        );
    }

    public function testValidWhenFilterIsUsed()
    {
        $filter = $this->createMock(FilterInterface::class);
        $filter->expects($this->once())->method('filter')->willReturn([
            [
                'code' => '0001',
                'groups' => ['all', 'group1'],
                'description' => 'foo',
            ],
        ]);

        $validator = new ConstantSymbolValidator($filter);

        $this->assertSame(
            ConstantSymbolValidator::ERROR_NONE,
            $validator->validate('0001', ['filter' => ['include' => ['all']]])
        );
    }
}
