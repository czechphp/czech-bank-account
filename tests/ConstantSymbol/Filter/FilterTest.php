<?php

namespace Czechphp\CzechBankAccount\Tests\ConstantSymbol\Filter;

use Czechphp\CzechBankAccount\ConstantSymbol\Filter\Filter;
use Czechphp\CzechBankAccount\ConstantSymbol\Loader\LoaderInterface;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    private const DATASET = [
        [
            'code' => '0001',
            'groups' => ['all', 'group1'],
            'description' => 'foo',
        ],
        [
            'code' => '0002',
            'groups' => ['all', 'group1', 'group2'],
            'description' => 'bar',
        ],
        [
            'code' => '0003',
            'groups' => ['all', 'group2', 'group3'],
            'description' => 'baz',
        ],
    ];

    public function testSuccess(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $this->assertEqualsCanonicalizing(self::DATASET, $filter->filter());
    }

    public function testIncludeSingleGroup(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $expected = [
            [
                'code' => '0001',
                'groups' => ['all', 'group1'],
                'description' => 'foo',
            ],
            [
                'code' => '0002',
                'groups' => ['all', 'group1', 'group2'],
                'description' => 'bar',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $filter->filter([
            'include' => ['group1'],
        ]));
    }

    public function testIncludeMultipleGroups(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $expected = [
            [
                'code' => '0002',
                'groups' => ['all', 'group1', 'group2'],
                'description' => 'bar',
            ],
            [
                'code' => '0003',
                'groups' => ['all', 'group2', 'group3'],
                'description' => 'baz',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $filter->filter([
            'include' => ['group2', 'group3'],
        ]));
    }

    public function testIncludeGroupAndCode(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $expected = [
            [
                'code' => '0001',
                'groups' => ['all', 'group1'],
                'description' => 'foo',
            ],
            [
                'code' => '0002',
                'groups' => ['all', 'group1', 'group2'],
                'description' => 'bar',
            ],
            [
                'code' => '0003',
                'groups' => ['all', 'group2', 'group3'],
                'description' => 'baz',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $filter->filter([
            'include' => ['group1', '0003'],
        ]));
    }

    public function testExcludeGroup(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $expected = [
            [
                'code' => '0003',
                'groups' => ['all', 'group2', 'group3'],
                'description' => 'baz',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $filter->filter([
            'include' => ['all'],
            'exclude' => ['group1'],
        ]));
    }

    public function testExcludeGroupAndCode(): void
    {
        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects($this->once())->method('load')->willReturn(self::DATASET);

        $filter = new Filter($loader);

        $expected = [
            [
                'code' => '0001',
                'groups' => ['all', 'group1'],
                'description' => 'foo',
            ],
        ];

        $this->assertEqualsCanonicalizing($expected, $filter->filter([
            'include' => ['all'],
            'exclude' => ['group2', '0003'],
        ]));
    }
}
