<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Filter;

final class ArrayCacheFilter implements FilterInterface
{
    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var array|null
     */
    private $lastCriteria;

    /**
     * @var array|null
     */
    private $lastFiltered;

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
