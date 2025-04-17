<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

interface ValidatorInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function validate(string $value, array $options = []): int;
}
