<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use function gettype;
use function is_array;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function sprintf;
use const JSON_ERROR_NONE;

final class JsonConverter implements ConverterInterface
{
    public function convert(string $content): array
    {
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new LogicException(sprintf('Unable to decode json due to error [%d] %s', json_last_error(), json_last_error_msg()));
        }

        if (!is_array($json)) {
            throw new LogicException(sprintf('Invalid data type %s expected array', gettype($json)));
        }

        return $json;
    }
}
