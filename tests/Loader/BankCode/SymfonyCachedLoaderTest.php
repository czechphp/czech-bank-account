<?php

declare(strict_types=1);

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\InvalidArgumentException;
use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\SymfonyCachedLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

final class SymfonyCachedLoaderTest extends TestCase
{
    public function testSuccess(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('get')->willReturn([]);

        $decoratorLoader = new SymfonyCachedLoader($loader, $cache);

        $this->assertSame([], $decoratorLoader->load());
    }

    public function testException(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $cache->expects($this->once())->method('get')->willThrowException(new InvalidArgumentException);

        $decoratorLoader = new SymfonyCachedLoader($loader, $cache);

        $this->expectException(InvalidArgumentException::class);

        $this->assertSame([], $decoratorLoader->load());
    }
}
