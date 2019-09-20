<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;

interface ConverterInterface
{
    /**
     * @param string $content
     *
     * @return array
     *
     * @throws LogicException
     */
    public function convert(string $content): array;
}
