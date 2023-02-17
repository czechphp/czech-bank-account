<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\ConverterInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\Converter\CsvConverter;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SymfonyHttpClientLoader implements LoaderInterface
{
    private HttpClientInterface $httpClient;
    private ConverterInterface $converter;
    private string $uri;

    public function __construct(HttpClientInterface $httpClient, ?ConverterInterface $converter = null, string $uri = self::DATA_REMOTE_CSV)
    {
        $this->httpClient = $httpClient;
        $this->converter = $converter ?: new CsvConverter();
        $this->uri = $uri;
    }

    /**
     * {@inheritDoc}
     */
    public function load(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->uri);

            return $this->converter->convert($response->getContent());
        } catch (ExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
