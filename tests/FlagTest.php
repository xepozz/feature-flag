<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Xepozz\FeatureFlag\InMemoryFlagStorage;
use Xepozz\FeatureFlag\Tests\Support\StringEnum;

class FlagTest extends TestCase
{
    #[DataProvider('dataIsActive')]
    public function testInGroup(array $flags, string|int|\BackedEnum $flag, bool $expectedResult): void
    {
        $storage = new InMemoryFlagStorage($flags);

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