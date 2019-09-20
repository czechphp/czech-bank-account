<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

interface ValidatorInterface
{
    public function validate(string $value, array $options = []): int;
}
