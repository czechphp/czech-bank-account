<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Filter;

use function trigger_deprecation;

trigger_deprecation('czechphp/czech-bank-account', '1.3.2', 'The "%s" class is deprecated, with no replacement.', ArrayCacheFilter::class);

/**
 * @deprecated since czechphp/czech-bank-account 1.3.2, with no replacement.
 */
final class ArrayCacheFilter implements FilterInterface
{
    private FilterInterface $filter;
    private ?array $lastCriteria = null;
    private ?array $lastFiltered = null;

    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    public function filter(array $criteria = ['include' => ['all']]): array
    {
        if ($this->lastCriteria === $criteria) {
            return $this->lastFiltered;
        }

        $this->lastCriteria = $criteria;

        return $this->lastFiltered = $this->filter->filter($criteria);
    }
}
