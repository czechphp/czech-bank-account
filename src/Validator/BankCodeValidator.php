<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

use Czechphp\CzechBankAccount\Loader\BankCode\LoaderInterface;

final class BankCodeValidator implements ValidatorInterface
{
    public const ERROR_NONE = 0;
    public const ERROR_INVALID_CODE = 1;
    public const ERROR_FORMAT = 2;

    private LoaderInterface $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    public function validate(string $value, array $options = []): int
    {
        $value = trim($value);

        if (preg_match('/^[0-9]{4}$/', $value) !== 1) {
            return self::ERROR_FORMAT;
        }

        $bankCodes = $this->loader->load();

        foreach ($bankCodes as $item) {
            if ($item[LoaderInterface::CODE] === $value) {
                return self::ERROR_NONE;
            }
        }

        return self::ERROR_INVALID_CODE;
    }
}
