<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Filter;

interface FilterInterface
{
    public function filter(array $criteria = []): array;
}
