<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Debug;

use BackedEnum;
use Xepozz\FeatureFlag\FlagStorageInterface;

final class FlagStorageInterfaceProxy implements FlagStorageInterface
{
    public function __construct(
        private FlagStorageInterface $decorated,
        private FeatureFlagCollector $collector,
    ) {
    }

    public function isActive(BackedEnum|int|string $flag): bool
    {
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function setFlag(BackedEnum|int|string $flag, bool $active): void
    {
        $this->collector->collect(
            'flags',
            __FUNCTION__,
            [
                'flag' => $flag,
                'active' => $active,
            ],
        );
        $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function getAll(): array
    {
        return $this->decorated->{__FUNCTION__}(...func_get_args());
    }

    public function collectAll(): void
    {
        $result = $this->decorated->getAll();
        $this->collector->collectStatic(
            'flags',
            [
                'flags' => $result,
            ],
        );
    }
}
