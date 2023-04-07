<?php

declare(strict_types=1);

use Xepozz\FeatureFlag\FlagStorageInterface;
use Xepozz\FeatureFlag\InMemoryFlagStorage;

return [
    FlagStorageInterface::class=> [
        'class' => InMemoryFlagStorage::class,
        '__construct()' => [
            'flags' => [],
        ],
    ],
];