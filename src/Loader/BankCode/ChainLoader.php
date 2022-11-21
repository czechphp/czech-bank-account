<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use Throwable;

final class ChainLoader implements LoaderInterface
{
    /**
     * @var LoaderInterface[]
     */
    private array $loaders;

    /**
     * @param LoaderInterface[] $loaders
     */
    public function __construct(array $loaders)
    {
        $this->loaders = $loaders;
    }

    public function load(): array
    {
        foreach ($this->loaders as $loader) {
            try {
                return $loader->load();
            } catch (Throwable $e) {
                continue;
            }
        }

        throw new LogicException('No chained loader was able to load the data');
    }
}
