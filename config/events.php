<?php

declare(strict_types=1);

use Xepozz\FeatureFlag\Debug\FlagStorageInterfaceProxy;
use Yiisoft\Yii\Http\Event\AfterRequest;
use Yiisoft\Yii\Http\Event\BeforeRequest;

return [
    BeforeRequest::class => [
        [FlagStorageInterfaceProxy::class, 'collectAll'],
    ],
    AfterRequest::class => [
        [FlagStorageInterfaceProxy::class, 'collectAll'],
    ],
];