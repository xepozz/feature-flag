<?php

declare(strict_types=1);

namespace Xepozz\FeatureFlag\Driver;

use BackedEnum;
use Redis;
use RedisException;
use Xepozz\FeatureFlag\FlagStorageInterface;

final class RedisDriver implements FlagStorageInterface
{
    public function __construct(
        private Redis $redis,
        private string $hashTableKey = 'ab',
    ) {
    }

    /**
     * @throws RedisException
     */
    public function isActive(BackedEnum|int|string $flag): bool
    {
        return $this->redis->hGet($this->hashTableKey, $this->serializeKey($flag)) === '1';
    }

    /**
     * @throws RedisException
     */
    public function setFlag(BackedEnum|int|string $flag, bool $active): void
    {
        $this->redis->hSet($this->hashTableKey, $this->serializeKey($flag), $active ? '1' : '0');
    }

    /**
     * @throws RedisException
     */
    public function getAll(): array
    {
        return $this->redis->hGetAll($this->hashTableKey);
    }

    /**
     * @param (BackedEnum|int|string)[] $flags
     * @throws \RedisException
     */
    public function setAll(array $flags): void
    {
        $result = [];
        foreach ($flags as $flag => $active) {
            $result[$this->serializeKey($flag)] = $active ? '1' : '0';
        }
        $this->redis->hMSet($this->hashTableKey, $result);
    }

    /**
     * @throws RedisException
     */
    public function clear(): void
    {
        $keys = array_keys($this->getAll());
        $this->redis->del($this->hashTableKey, ...$keys);
    }

    protected function serializeKey(BackedEnum|int|string $flag): string
    {
        return (string)(is_scalar($flag) ? $flag : $flag->value);
    }
}