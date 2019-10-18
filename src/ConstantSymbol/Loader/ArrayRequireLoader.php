<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Loader;

use InvalidArgumentException;
use function file_exists;
use function sprintf;

final class ArrayRequireLoader implements LoaderInterface
{
    public const DATA_FILE = __DIR__ . '/../../../data/constant-symbols.php';

    /**
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $filename;

    public function __construct(string $filename = self::DATA_FILE)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf("File '%s' does not exist", $filename));
        }

        $this->filename = $filename;
    }

    public function load(): array
    {
        if ($this->data === null) {
            $this->data = require $this->filename;
        }

        return $this->data;
    }
}
