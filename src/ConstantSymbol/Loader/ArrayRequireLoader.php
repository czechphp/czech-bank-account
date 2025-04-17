<?php

declare(strict_types = 1);

namespace Czechphp\CzechBankAccount\ConstantSymbol\Loader;

use InvalidArgumentException;
use function file_exists;
use function sprintf;
use function trigger_deprecation;

trigger_deprecation('czechphp/czech-bank-account', '1.3.2', 'The "%s" class is deprecated, with no replacement.', ArrayRequireLoader::class);

/**
 * @deprecated since czechphp/czech-bank-account 1.3.2, with no replacement.
 */
final class ArrayRequireLoader implements LoaderInterface
{
    public const DATA_FILE = __DIR__ . '/../../../data/constant-symbols.php';

    private string $filename;
    private ?array $data = null;

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
