<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Loader;

interface LoaderInterface
{
    public const CODE = 'code';
    public const GROUPS = 'groups';
    public const DESCRIPTION = 'description';

    public function load(): array;
}
