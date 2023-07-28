<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Tests\Driver;

use Xepozz\FeatureFlag\Driver\InMemoryDriver;
use Xepozz\FeatureFlag\FlagStorageInterface;
use Xepozz\FeatureFlag\Tests\AbstractFlagStorageTestCase;

final class InMemoryDriverTest extends AbstractFlagStorageTestCase
{
    protected function createDriver(array $flags): FlagStorageInterface
    {
        return new InMemoryDriver($flags);
    }
}