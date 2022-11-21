<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\ChainLoader;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;
use PHPUnit\Framework\TestCase;

final class ChainLoaderTest extends TestCase
{
    public function testFirstSuccess(): void
    {
        $loader1 = $this->createMock(LoaderInterface::class);
        $loader1->expects($this->once())->method('load')->willReturn([]);

        $loaders = [
            $loader1,
        ];

        $bankCode = new ChainLoader($loaders);

        $this->assertSame([], $bankCode->load());
    }

    public function testSecondSuccess(): void
    {
        $loader1 = $this->createMock(LoaderInterface::class);
        $loader1->expects($this->once())->method('load')->willThrowException(new LogicException());

        $loader2 = $this->createMock(LoaderInterface::class);
        $loader2->expects($this->once())->method('load')->willReturn([]);

        $loaders = [
            $loader1,
            $loader2,
        ];

        $bankCode = new ChainLoader($loaders);

        $this->assertSame([], $bankCode->load());
    }

    public function testEmpty(): void
    {
        $bankCode = new ChainLoader([]);

        $this->expectException(LogicException::class);

        $bankCode->load();
    }
}
