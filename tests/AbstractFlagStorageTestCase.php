<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xepozz\FeatureFlag\FlagStorageInterface;
use Xepozz\FeatureFlag\Tests\Support\StringEnum;

abstract class AbstractFlagStorageTestCase extends TestCase
{
    abstract protected function createDriver(array $flags): FlagStorageInterface;

    #[DataProvider('dataIsActive')]
    public function testIsActive(array $flags, string|int|\BackedEnum $flag, bool $expectedResult): void
    {
        $storage = $this->createDriver($flags);

        $actualResult = $storage->isActive($flag);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public static function dataIsActive(): iterable
    {
        yield [
            [],
            'feature_1',
            false,
        ];
        yield [
            ['feature_1' => true],
            'feature_1',
            true,
        ];
        yield [
            ['feature_1' => false],
            'feature_1',
            false,
        ];
        yield [
            ['feature_1' => true],
            'feature_2',
            false,
        ];
        yield [
            [123 => true],
            123,
            true,
        ];
        yield [
            [StringEnum::CASE1->value => true],
            StringEnum::CASE1,
            true,
        ];
        yield [
            [StringEnum::CASE1->value => true],
            StringEnum::CASE2,
            false,
        ];
        yield [
            [
                123 => true,
                'feature_1' => true,
                StringEnum::CASE1->value => true,
            ],
            StringEnum::CASE2,
            false,
        ];
        yield [
            [
                123 => true,
                'feature_1' => true,
                StringEnum::CASE1->value => true,
            ],
            StringEnum::CASE1,
            true,
        ];
    }
}