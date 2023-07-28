<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Driver;

use BackedEnum;
use InvalidArgumentException;
use Xepozz\FeatureFlag\FlagStorageInterface;

final class InMemoryDriver implements FlagStorageInterface
{
    public function __construct(private array $flags = [])
    {
        foreach ($this->flags as $flag => $active) {
            if (!is_bool($active)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Flag "%s" value should have boolean type, "%s" given instead.',
                        $flag,
                        is_scalar($active) ? $active : get_debug_type($active),
                    )
                );
            }
        }
    }

    public function isActive(string|int|BackedEnum $flag): bool
    {
        if ($flag instanceof BackedEnum) {
            $flag = $flag->value;
        }
        if (!isset($this->flags[$flag])) {
            return false;
        }
        return $this->flags[$flag] === true;
    }

    public function setFlag(string|int|BackedEnum $flag, bool $active): void
    {
        $this->flags[$flag] = $active;
    }

    public function getAll(): array
    {
        return $this->flags;
    }
}