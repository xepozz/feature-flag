<?php

declare(strict_types=1);

use Xepozz\FeatureFlag\Debug\ExperimentStorageInterfaceProxy;
use Xepozz\FeatureFlag\Debug\FeatureFlagCollector;
use Xepozz\FeatureFlag\Debug\FlagStorageInterfaceProxy;
use Xepozz\FeatureFlag\Experiment\ExperimentStorageInterface;
use Xepozz\FeatureFlag\FlagStorageInterface;

return [
    'yiisoft/yii-debug' => [
        'collectors' => [
            FeatureFlagCollector::class,
        ],
        'trackedServices' => [
            ExperimentStorageInterface::class => [ExperimentStorageInterfaceProxy::class, FeatureFlagCollector::class],
            FlagStorageInterface::class => [FlagStorageInterfaceProxy::class, FeatureFlagCollector::class],
        ],
    ],
];