<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Validator;

use function array_reverse;
use function count;
use function preg_match;
use function str_split;

/**
 * Validation is based on Czech law 169/2011 Sb.
 *
 * @link https://www.psp.cz/sqw/sbirka.sqw?cz=169&r=2011
 * @link https://www.zakonyprolidi.cz/cs/2011-169
 */
final class BankAccountNumberValidator implements ValidatorInterface
{
    public const OPTION_TYPE = 'type';

    /**
     * Variable length is bank account number without leading zeros and dash as separator. For example: 19-19
     */
    public const OPTION_TYPE_VARIABLE = 'variable';

    /**
     * Constant length is bank account number of 16 digits with leading zeros and without any separator. For example: 0000190000000019
     */
    public const OPTION_TYPE_CONSTANT = 'constant';

    public const ERROR_NONE = 0;
    public const ERROR_FORMAT_VARIABLE = 1;
    public const ERROR_FORMAT_CONSTANT = 2;
    public const ERROR_FIRST_PART_CHECKSUM = 3;
    public const ERROR_AMOUNT_OF_NON_ZERO_DIGITS = 4;
    public const ERROR_SECOND_PART_CHECKSUM = 5;

    /**
     * Regular expression of account number with variable length and dash as separator
     */
    private const REGEX_VARIABLE = '/^((?<first>[0-9]{1,6})-)?(?<second>[0-9]{2,10})$/';

    /**
     * Regular expression of account number composed of only digits with constant length
     */
    private const REGEX_CONSTANT = '/^(?<first>[0-9]{6})(?<second>[0-9]{10})$/';

    /**
     * Weights in format where key is n and value is weight
     * It's not indexed from zero for easy validation against documentation (law)
     */
    private const WEIGHTS = [
        1 => 1,
        2 => 2,
        3 => 4,
        4 => 8,
        5 => 5,
        6 => 10,
        7 => 9,
        8 => 7,
        9 => 3,
        10 => 6,
    ];

    public function validate(string $value, array $options = []): int
    {
        $options = $this->defaultOptions($options);

        switch ($options[self::OPTION_TYPE]) {
            case self::OPTION_TYPE_VARIABLE:
                if (1 !== preg_match(self::REGEX_VARIABLE, $value, $matches)) {
                    return self::ERROR_FORMAT_VARIABLE;
                }
                break;
            case self::OPTION_TYPE_CONSTANT:
                if (1 !== preg_match(self::REGEX_CONSTANT, $value, $matches)) {
                    return self::ERROR_FORMAT_CONSTANT;
                }
                break;
            default:
                return self::ERROR_NONE;
        }

        // if first part is not empty then it must have a valid checksum
        if ('' !== $matches['first'] && false === $this->isChecksumValid($matches['first'])) {
            return self::ERROR_FIRST_PART_CHECKSUM;
        }

        // must contain at least 2 non zero digits
        if (false === $this->hasAtLeastTwoNonZeroDigits($matches['second'])) {
            return self::ERROR_AMOUNT_OF_NON_ZERO_DIGITS;
        }

        // checksum must be valid
        if (false === $this->isChecksumValid($matches['second'])) {
            return self::ERROR_SECOND_PART_CHECKSUM;
        }

        return self::ERROR_NONE;
    }

    private function defaultOptions(array $options): array
    {
        return array_replace([
            self::OPTION_TYPE => self::OPTION_TYPE_VARIABLE,
        ], $options);
    }

    private function isChecksumValid(string $string): bool
    {
        $chars = array_reverse(str_split($string));
        $count = count($chars);
        $sum = 0;

        for ($i = 0; $i < $count; $i++) {
            $sum += $chars[$i] * self::WEIGHTS[$i + 1];
        }

        return $sum % 11 === 0;
    }

    private function hasAtLeastTwoNonZeroDigits(string $string): bool
    {
        if (preg_match('/(?>0*[1-9]0*){2,}/', $string) === 1) {
            return true;
        }

        return false;
    }
}
