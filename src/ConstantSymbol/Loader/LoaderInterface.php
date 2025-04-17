<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Loader;

use function trigger_deprecation;

trigger_deprecation('czechphp/czech-bank-account', '1.3.2', 'The "%s" class is deprecated, with no replacement.', LoaderInterface::class);

/**
 * @deprecated since czechphp/czech-bank-account 1.3.2, with no replacement.
 */
interface LoaderInterface
{
    public const CODE = 'code';
    public const GROUPS = 'groups';
    public const DESCRIPTION = 'description';

    public function load(): array;
}
