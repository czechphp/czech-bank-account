<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\CsvConverter;
use PHPUnit\Framework\TestCase;

final class CsvConverterTest extends TestCase
{
    public function testEmpty()
    {
        $converter = new CsvConverter();

        $this->assertSame([], $converter->convert(''));
    }

    public function testValid()
    {
        $converter = new CsvConverter();
        $contents = <<<CSV
Kód platebního styku;Poskytovatel platebních služeb;BIC kód (SWIFT);Systém CERTIS
0100;Komerční banka, a.s.;KOMBCZPP;A
8297;EUPSProvider s.r.o.;;-

CSV;

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
}
