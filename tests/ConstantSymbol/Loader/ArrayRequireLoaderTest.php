<?php

namespace Czechphp\CzechBankAccount\Tests\ConstantSymbol\Loader;

use Czechphp\CzechBankAccount\ConstantSymbol\Loader\ArrayRequireLoader;
use Czechphp\CzechBankAccount\ConstantSymbol\Loader\LoaderInterface;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ArrayRequireLoaderTest extends TestCase
{
    public function testDefaultDataHasExpectedFormat()
    {
        $loader = new ArrayRequireLoader();
        $data = $loader->load();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        foreach ($data as $item) {
            $this->assertArrayHasKey(LoaderInterface::CODE, $item);
            $this->assertArrayHasKey(LoaderInterface::GROUPS, $item);
            $this->assertArrayHasKey(LoaderInterface::DESCRIPTION, $item);

            $this->assertIsString($item[LoaderInterface::CODE]);
            $this->assertIsArray($item[LoaderInterface::GROUPS]);
            $this->assertIsString($item[LoaderInterface::DESCRIPTION]);

            foreach ($item[LoaderInterface::GROUPS] as $key => $group) {
                $this->assertIsInt($key);
                $this->assertIsString($group);
            }
        }
    }

    public function testFailsOnInvalidFilename()
    {
        $this->expectException(InvalidArgumentException::class);

        new ArrayRequireLoader(__DIR__ . '/iDontExist');
    }
}
