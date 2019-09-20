<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode\Converter;

use Czechphp\CzechBankAccount\Loader\BankCode\ChainLoader;
use function fgetcsv;
use function fopen;
use function fwrite;
use function preg_match;
use function trim;

final class CsvConverter implements ConverterInterface
{
    public const DELIMITER = ';';
    private const INDEX_CODE = 0;
    private const INDEX_NAME = 1;
    private const INDEX_BIC = 2;
    private const INDEX_CERTIS = 3;

    /**
     * @var string
     */
    private $delimiter;

    public function __construct(string $delimiter = self::DELIMITER)
    {
        $this->delimiter = $delimiter;
    }

    public function convert(string $content): array
    {
        $handle = fopen('php://memory', 'w+');

        fwrite($handle, $content);
        rewind($handle);

        $data = [];

        while (($row = fgetcsv($handle, 0, $this->delimiter)) !== false) {
            if (preg_match('/^\d{4}$/', trim($row[self::INDEX_CODE])) === 1) {
                $data[] = [
                    ChainLoader::CODE => trim($row[self::INDEX_CODE]),
                    ChainLoader::NAME => trim($row[self::INDEX_NAME]),
                    ChainLoader::BIC => trim($row[self::INDEX_BIC]) ?: null,
                    ChainLoader::CERTIS => trim($row[self::INDEX_CERTIS]) === 'A',
                ];
            }
        }

        fclose($handle);

        return $data;
    }
}
