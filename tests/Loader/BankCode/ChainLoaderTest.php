<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\ChainLoader;
use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;
use PHPUnit\Framework\TestCase;

final class ChainLoaderTest extends TestCase
{
    public function testFirstSuccess()
    {
        $loader1 = $this->createMock(LoaderInterface::class);
        $loader1->expects($this->once())->method('load')->willReturn([]);

        $loaders = [
            $loader1,
        ];

        $bankCode = new ChainLoader($loaders);

        $this->assertSame([], $bankCode->load());
    }
}
