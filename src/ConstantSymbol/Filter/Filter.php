<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Filter;

use Czechphp\CzechBankAccount\ConstantSymbol\Loader\LoaderInterface;
use function array_filter;
use function in_array;

final class Filter implements FilterInterface
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function filter(array $criteria = ['include' => ['all']]): array
    {
        $include = isset($criteria['include']) ? $criteria['include'] : [];
        $exclude = isset($criteria['exclude']) ? $criteria['exclude'] : [];

        $filtered = array_filter($this->loader->load(), function (array $item) use ($include, $exclude) {
            $code = $item[LoaderInterface::CODE];
            $groups = $item[LoaderInterface::GROUPS];

            return $this->decider($code, $groups, $include) && !$this->decider($code, $groups, $exclude);
        });

        return $filtered;
    }

    private function decider(string $code, array $groups, array $filter): bool
    {
        if (in_array($code, $filter)) {
            return true;
        }

        foreach ($groups as $group) {
            if (in_array($group, $filter)) {
                return true;
            }
        }

        return false;
    }
}
