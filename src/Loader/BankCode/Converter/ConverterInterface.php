<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;

interface ConverterInterface
{
    /**
     * @return array<array-key, array{0: string, 1: string, 2: string|null, 3: bool}>
     *
     * @throws LogicException
     */
    public function convert(string $content): array;
}
