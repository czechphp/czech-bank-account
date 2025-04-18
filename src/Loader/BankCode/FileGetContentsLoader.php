<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\ConverterInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\Converter\CsvConverter;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;

final class FileGetContentsLoader implements LoaderInterface
{
    private ConverterInterface $converter;
    private string $uri;

    public function __construct(ConverterInterface $converter = null, string $uri = self::DATA_REMOTE_CSV)
    {
        $this->converter = $converter ?: new CsvConverter();
        $this->uri = $uri;
    }

    /**
     * {@inheritDoc}
     */
    public function load(): array
    {
        $level = error_reporting(0);
        $content = file_get_contents($this->uri);
        error_reporting($level);

        if ($content === false) {
            $error = error_get_last();

            throw new LogicException($error['message']);
        }

        return $this->converter->convert($content);
    }
}
