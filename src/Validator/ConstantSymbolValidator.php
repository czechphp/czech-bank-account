<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

use Czechphp\CzechBankAccount\ConstantSymbol\Filter\FilterInterface;
use Czechphp\CzechBankAccount\ConstantSymbol\Loader\LoaderInterface;
use function preg_match;
use function trim;

final class ConstantSymbolValidator implements ValidatorInterface
{
    public const ERROR_NONE = 0;
    public const ERROR_FORMAT = 1;
    public const ERROR_INVALID_CODE = 2;

    /**
     * @var FilterInterface|null
     */
    private $filter;

    public function __construct(FilterInterface $filter = null)
    {
        $this->filter = $filter;
    }

    public function validate(string $value, array $options = []): int
    {
        $value = trim($value);

        if (preg_match('/^\d{0,4}$/', $value) !== 1) {
            return self::ERROR_FORMAT;
        }

        if ($this->filter !== null && isset($options['filter'])) {
            if ($this->isCodeInExpectedRange($value, $options) === false) {
                return self::ERROR_INVALID_CODE;
            }
        }

        return self::ERROR_NONE;
    }

    private function isCodeInExpectedRange(string $value, array $options): bool
    {
        $filtered = $this->filter->filter($options['filter']);

        foreach ($filtered as $item) {
            if ($item[LoaderInterface::CODE] === $value) {
                return true;
            }
        }

        return false;
    }
}
