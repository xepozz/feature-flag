<?php

declare(strict_types=1);

use Xepozz\FeatureFlag\Driver\InMemoryDriver;
use Xepozz\FeatureFlag\Driver\RedisDriver;
use Xepozz\FeatureFlag\FlagStorageInterface;

/**
 * @var array $params
 */

return [
    FlagStorageInterface::class => InMemoryDriver::class,
    InMemoryDriver::class => [
        '__construct()' => [
            'flags' => (array) ($params['xepozz/feature-flag']['flags'] ?? []),
        ],
    ],
    RedisDriver::class => function () {
        $redis = new Redis();
        $redis->pconnect(
            host: '127.0.0.1',
            port: 6379,
            timeout: 2.5,
        );
        return new RedisDriver($redis, 'ab');
    },
];