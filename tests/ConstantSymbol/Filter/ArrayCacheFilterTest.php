<?php

namespace Czechphp\CzechBankAccount\Tests\ConstantSymbol\Filter;

use Czechphp\CzechBankAccount\ConstantSymbol\Filter\ArrayCacheFilter;
use Czechphp\CzechBankAccount\ConstantSymbol\Filter\FilterInterface;
use PHPUnit\Framework\TestCase;

class ArrayCacheFilterTest extends TestCase
{
    public function testCachedDataIsUsed()
    {
        $decoratedFilter = $this->createMock(FilterInterface::class);
        $decoratedFilter->expects($this->once())->method('filter')->willReturn([]);

        $filter = new ArrayCacheFilter($decoratedFilter);

        $this->assertEqualsCanonicalizing([], $filter->filter(['include' => ['all']]));
        $this->assertEqualsCanonicalizing([], $filter->filter(['include' => ['all']]));
    }

    public function testCachedDataIsDiscardedOnNewCriteria()
    {
        $decoratedFilter = $this->createMock(FilterInterface::class);
        $decoratedFilter->expects($this->exactly(2))->method('filter')->willReturn([]);

        $filter = new ArrayCacheFilter($decoratedFilter);

        $this->assertEqualsCanonicalizing([], $filter->filter(['include' => ['all']]));
        $this->assertEqualsCanonicalizing([], $filter->filter(['include' => ['public']]));
    }
}
