<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Converter\ConverterInterface;
use Czechphp\CzechBankAccount\Loader\BankCode\Converter\JsonConverter;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\InvalidArgumentException;
use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use function is_readable;
use function sprintf;
use function strlen;
use const PHP_MAXPATHLEN;

final class FilesystemLoader implements LoaderInterface
{
    private string $filename;
    private ConverterInterface $converter;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $filename = self::DATA_LOCAL_JSON, ConverterInterface $converter = null)
    {
        $this->filename = $filename;
        $this->converter = $converter ?: new JsonConverter();

        if (strlen($this->filename) > $this->getMaxPathLength()) {
            throw new InvalidArgumentException(sprintf('Filename path length exceeds maximum of %d characters.', $this->getMaxPathLength()));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function load(): array
    {
        if (!is_readable($this->filename)) {
            throw new LogicException(sprintf('Filename "%s" is not readable', $this->filename));
        }

        $content = file_get_contents($this->filename);

        if ($content === false) {
            throw new LogicException(sprintf('Could not read the file "%s"', $this->filename));
        }

        return $this->converter->convert($content);
    }

    private function getMaxPathLength(): int
    {
        return PHP_MAXPATHLEN - 2;
    }
}
