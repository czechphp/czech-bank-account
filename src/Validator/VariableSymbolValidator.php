<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

use function preg_match;
use function trim;

final class VariableSymbolValidator implements ValidatorInterface
{
    public const ERROR_NONE = 0;
    public const ERROR_FORMAT = 1;

    /**
     * {@inheritDoc}
     */
    public function validate(string $value, array $options = []): int
    {
        if (preg_match('/^\d{0,10}$/', trim($value)) !== 1) {
            return self::ERROR_FORMAT;
        }

        return self::ERROR_NONE;
    }
}
