<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag;

use BackedEnum;

interface FlagStorageInterface
{
    public function isActive(string|int|BackedEnum $flag): bool;

    public function setFlag(string|int|BackedEnum $flag, bool $active): void;

    /**
     * @return (string|int|BackedEnum)[]
     */
    public function getAll(): array;
}