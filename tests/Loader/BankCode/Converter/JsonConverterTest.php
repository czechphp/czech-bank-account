<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\JsonConverter;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use PHPUnit\Framework\TestCase;

final class JsonConverterTest extends TestCase
{
    public function testEmpty(): void
    {
        $converter = new JsonConverter();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Syntax error');

        $converter->convert('');
    }

    public function testValid(): void
    {
        $converter = new JsonConverter();

        $contents = <<<JSON
[
    [
        "0100",
        "Komerční banka, a.s.",
        "KOMBCZPP",
        true
    ],
    [
        "8297",
        "EUPSProvider s.r.o.",
        null,
        false
    ]
]
JSON;

        $expects = [
            [
                0 => '0100',
                1 => 'Komerční banka, a.s.',
                2 => 'KOMBCZPP',
                3 => true,
            ],
            [
                0 => '8297',
                1 => 'EUPSProvider s.r.o.',
                2 => null,
                3 => false,
            ],
        ];

        $this->assertSame($expects, $converter->convert($contents));
    }

    public function testInvalidJson(): void
    {
        $converter = new JsonConverter();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Syntax error');

        $converter->convert('This is not valid json');
    }

    public function testNullJson(): void
    {
        $converter = new JsonConverter();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Invalid data type NULL expected array');

        $converter->convert('null');
    }
}
