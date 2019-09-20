<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Utils;

use InvalidArgumentException;
use function ltrim;
use function preg_match;
use function sprintf;
use function str_pad;
use function trim;
use const STR_PAD_LEFT;

final class BankAccountNumberFormatConverter
{
    /**
     * Converts bank account number from variable length format to constant length format.
     * Acceptable separator is whitespace and dash character. For example "19-19" or "19 19".
     *
     * @param string $string
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function convertVariableToConstant(string $string): string
    {
        if (preg_match('/^((?<first>[0-9]{0,6})[\s|-]+)?(?<second>[0-9]{0,10})$/', trim($string), $matches) !== 1) {
            throw new InvalidArgumentException(sprintf('Provided string "%s" is not bank account number of variable length format', $string));
        }

        return str_pad($matches['first'], 6, '0', STR_PAD_LEFT) . str_pad($matches['second'], 10, '0', STR_PAD_LEFT);
    }

    /**
     * Converts bank account number from constant length format to variable length format.
     *
     * @param string $string
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public static function convertConstantToVariable(string $string): string
    {
        if (preg_match('/^(?<first>[0-9]{6})(?<second>[0-9]{10})$/', trim($string), $matches) !== 1) {
            throw new InvalidArgumentException(sprintf('Provided string "%s" is not bank account number of constant length format', $string));
        }

        $first = ltrim($matches['first'], '0');
        $second = ltrim($matches['second'], '0');

        if ($first === '') {
            return $second;
        }

        return sprintf('%s-%s', $first, $second);
    }
}
