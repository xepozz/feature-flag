<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Tests\Driver;

use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use Redis;
use Xepozz\FeatureFlag\Driver\RedisDriver;
use Xepozz\FeatureFlag\FlagStorageInterface;
use Xepozz\FeatureFlag\Tests\AbstractFlagStorageTestCase;

#[RequiresPhpExtension(extension: 'redis')]
final class RedisDriverTest extends AbstractFlagStorageTestCase
{
    public static Redis $redisConnection;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$redisConnection = new Redis();
        self::$redisConnection->pconnect(
            host: '127.0.0.1',
            port: 6379,
            timeout: 2.5,
        );
    }

    protected function createDriver(array $flags): FlagStorageInterface
    {
        $driver = new RedisDriver(self::$redisConnection);
        $driver->clear();
        $driver->setAll($flags);
        return $driver;
    }
}