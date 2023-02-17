<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\InvalidArgumentException;
use Psr\Cache\InvalidArgumentException as PsrInvalidArgumentException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

final class SymfonyCachedLoader implements LoaderInterface
{
    public const CACHE_KEY = 'czech_php_czech_bank_account_bank_codes';

    private LoaderInterface $loader;
    private CacheInterface $cache;
    private int $expiresAfter;
    private string $cacheKey;
    private ?float $beta;

    public function __construct(LoaderInterface $loader, CacheInterface $cache, int $expiresAfter = 86400, string $cacheKey = self::CACHE_KEY, ?float $beta = null)
    {
        $this->loader = $loader;
        $this->cache = $cache;
        $this->expiresAfter = $expiresAfter;
        $this->cacheKey = $cacheKey;
        $this->beta = $beta;
    }

    /**
     * {@inheritDoc}
     */
    public function load(): array
    {
        try {
            return $this->cache->get($this->cacheKey, function (ItemInterface $item) {
                $item->expiresAfter($this->expiresAfter);

                return $this->loader->load();
            }, $this->beta);
        } catch (PsrInvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
