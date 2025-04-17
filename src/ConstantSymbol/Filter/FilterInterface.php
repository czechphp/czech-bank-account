<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Filter;

trigger_deprecation('czechphp/czech-bank-account', '1.3.2', 'The "%s" class is deprecated, with no replacement.', FilterInterface::class);

/**
 * @deprecated since czechphp/czech-bank-account 1.3.2, with no replacement.
 */
interface FilterInterface
{
    public function filter(array $criteria = []): array;
}
