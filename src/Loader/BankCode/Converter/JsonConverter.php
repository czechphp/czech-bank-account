<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use JsonException;
use function gettype;
use function is_array;
use function json_decode;
use function sprintf;
use const JSON_THROW_ON_ERROR;

final class JsonConverter implements ConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function convert(string $content): array
    {
        try {
            $json = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }

        if (!is_array($json)) {
            throw new LogicException(sprintf('Invalid data type %s expected array', gettype($json)));
        }

        return $json;
    }
}
