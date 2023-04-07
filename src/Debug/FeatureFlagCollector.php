<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Debug;

use Yiisoft\Yii\Debug\Collector\CollectorInterface;
use Yiisoft\Yii\Debug\Collector\CollectorTrait;

final class FeatureFlagCollector implements CollectorInterface
{
    use CollectorTrait;

    private array $static = [];
    private array $calls = [];

    public function getCollected(): array
    {
        return [
            'calls' => $this->calls,
            'static' => $this->static,
        ];
    }

    public function collect(
        string $scope,
        string $method,
        array $arguments,
    ): void {
        $this->calls[$scope][] = [
            'method' => $method,
            'arguments' => $arguments,
        ];
    }

    public function collectStatic(
        string $scope,
        array $arguments,
    ): void {
        $this->static[$scope][] = $arguments;
    }
}
