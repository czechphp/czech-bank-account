<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\ConverterInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use Czechphp\CzechBankAccount\Loader\BankCode\FileGetContentsLoader;
use PHPUnit\Framework\TestCase;

final class FileGetContentsLoaderTest extends TestCase
{
    public function testSuccess(): void
    {
        $converter = $this->createMock(ConverterInterface::class);

        $converter->expects($this->once())->method('convert')->willReturn(['foo']);

        $handler = new FileGetContentsLoader($converter, 'php://memory');

        $this->assertSame(['foo'], $handler->load());
    }

    public function testFail(): void
    {
        $converter = $this->createMock(ConverterInterface::class);

        $converter->expects($this->never())->method('convert');

        $this->expectException(LogicException::class);

        $handler = new FileGetContentsLoader($converter, './notfound');

        $this->assertSame(null, $handler->load());
    }
}
