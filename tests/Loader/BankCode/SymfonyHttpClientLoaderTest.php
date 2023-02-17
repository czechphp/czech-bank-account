<?php

declare(strict_types=1);

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\ConverterInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\RuntimeException;
use Czechphp\CzechBankAccount\Loader\BankCode\SymfonyHttpClientLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class SymfonyHttpClientLoaderTest extends TestCase
{
    public function testSuccess(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())->method('getContent')->willReturn('');

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())->method('request')->willReturn($response);

        $converter = $this->createMock(ConverterInterface::class);
        $converter->expects($this->once())->method('convert')->willReturn([]);

        $loader = new SymfonyHttpClientLoader($httpClient, $converter);

        $this->assertSame([], $loader->load());
    }

    public function testException(): void
    {
        $exception = $this->createMock(TransportExceptionInterface::class);

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->expects($this->once())->method('request')->willThrowException($exception);

        $converter = $this->createMock(ConverterInterface::class);

        $loader = new SymfonyHttpClientLoader($httpClient, $converter);

        $this->expectException(RuntimeException::class);

        $this->assertSame([], $loader->load());
    }
}
