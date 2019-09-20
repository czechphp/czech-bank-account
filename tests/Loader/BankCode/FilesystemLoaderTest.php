<?php

namespace Czechphp\CzechBankAccount\Tests\Loader\BankCode;

use Czechphp\CzechBankAccount\Loader\BankCode\Exception\LogicException;
use Czechphp\CzechBankAccount\Loader\BankCode\FilesystemLoader;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use function count;
use function str_pad;
use const PHP_MAXPATHLEN;

final class FilesystemLoaderTest extends TestCase
{
    public function testSuccess()
    {
        $handler = new FilesystemLoader();

        $this->assertGreaterThan(0, count($handler->load()));
    }

    public function testInvalidPathLength()
    {
        $filename = str_pad('/', PHP_MAXPATHLEN, 'a');

        $this->expectException(InvalidArgumentException::class);

        new FilesystemLoader($filename);
    }

    public function testNonReadable()
    {
        $handler = new FilesystemLoader(__DIR__ . '/this_does_not_exist.json');

        $this->expectException(LogicException::class);

        $this->assertSame([], $handler->load());
    }
}
