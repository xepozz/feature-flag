# Feature Flag

This is a simple library to enable/disable features based on a set of rules.

[![Latest Stable Version](https://poser.pugx.org/xepozz/feature-flag/v/stable.svg)](https://packagist.org/packages/xepozz/feature-flag)
[![Total Downloads](https://poser.pugx.org/xepozz/feature-flag/downloads.svg)](https://packagist.org/packages/xepozz/feature-flag)
[![phpunit](https://github.com/xepozz/feature-flag/workflows/PHPUnit/badge.svg)](https://github.com/xepozz/feature-flag/actions)
[![codecov](https://codecov.io/gh/xepozz/feature-flag/branch/master/graph/badge.svg?token=UREXAOUHTJ)](https://codecov.io/gh/xepozz/feature-flag)
[![type-coverage](https://shepherd.dev/github/xepozz/feature-flag/coverage.svg)](https://shepherd.dev/github/xepozz/feature-flag)

## Installation

```shell
composer require xepozz/feature-flag
```

## Configuration

Choose the driver you want to use. Currently, the library supports the following drivers:
- [InMemory](src/Driver/InMemoryDriver.php) - stores data in memory. This driver is used by default.
- [Redis](src/Driver/RedisDriver.php) - stores data in Redis. Uses [phpredis extension](https://github.com/phpredis/phpredis#installation)

### InMemory

Configure the driver in the dependency injection container configuration file:

`di.php`
```php
\Xepozz\FeatureFlag\Driver\InMemoryDriver::class => [
    '__construct()' => [
        'flags' => [
            155 => false,
            'feature_name' => true,
            FeaturesEnum::FEATURE_NAME => true,
        ],
    ],
],
```

Or with `params.php`:

```php
'xepozz/feature-flag' => [
    'flags' => [
        155 => false,
        'feature_name' => true,
        FeaturesEnum::FEATURE_NAME => true,
    ],
],
```

> Configuring the driver with `params.php` is only available for the `InMemoryDriver`.

### Redis

Configure the driver in the dependency injection container configuration file:

`di.php`
```php
\Xepozz\FeatureFlag\Driver\RedisDriver::class => function () {
    $redis = new Redis();
    $redis->pconnect(
        host: '127.0.0.1',
        port: 6379,
        timeout: 2.5,
    );

    return new \Xepozz\FeatureFlag\Driver\RedisDriver(redis: $redis, hashTableKey: 'ab');
},
```

The driver uses a hash table functions to store and retrieve data.
Read more about the hash table functions [here](https://redis.io/commands/?group=hash).

### Choose a driver

After you have chosen a driver, you need to configure the dependency injection container:

`di.php`

```php
use Xepozz\FeatureFlag\FlagStorageInterface;
use \Xepozz\FeatureFlag\Driver\RedisDriver;

return [
    // ...
    FlagStorageInterface::class => RedisDriver::class,
    // ...
]
````

## Usage

Pull `\Xepozz\FeatureFlag\FlagStorageInterface` from the dependency injection container and use it:

### `isActive(string|int|BackedEnum $flag): bool`

```php
use Xepozz\FeatureFlag\FlagStorageInterface;

class Controller
{
    public function index(FlagStorageInterface $flagStorage)
    {
        if ($flagStorage->isActive('feature_name')) {
            // feature is enabled
        } else {
            // feature is disabled
        }
    }
}
```

### `setFlag(string|int|BackedEnum $flag, bool $active): void`

> Be careful, in case of using not the `InMemoryDriver`, the flag will be stored permanently.

> In case of using the `InMemoryDriver`, the flag will be stored only for the current request. 
> So you can switch the flag depending on the conditions in your code. 
> For instance, you can enable the feature only for trusted IP addresses.

```php
use Xepozz\FeatureFlag\FlagStorageInterface;

class Controller
{
    public function index(FlagStorageInterface $flagStorage)
    {
        if ($condition) {
            $flagStorage->setFlag('feature_name', true);
        }
    }
}
```

### `getAll(): array`

Returns all flags as an associative array `array<string, bool>`. 

The only `InMemoryDriver` supports returning `BackendEnum` as a key, because it does not need to serialize the key.

The key is the flag name, the value is the flag state.

```php
use Xepozz\FeatureFlag\FlagStorageInterface;

class Controller
{
    public function index(FlagStorageInterface $flagStorage)
    {
        $flags = $flagStorage->getAll();
        // ...
    }
}
```

## Testing

#### Redis

Redis driver requires [phpredis extension](https://github.com/phpredis/phpredis) and a running Redis server. 

You can use the following command to start a Redis server in a Docker container:

```shell
docker run --rm -p 6379:6379 redis
```

Or use docker-compose:

```shell
docker-compose up -d
```

### Run tests:

```shell
composer test
```
Or
```shell
./vendor/bin/phpunit
```

## Looking for more modules?

- [Unique ID](https://github.com/xepozz/unique-id) - Allows you to track the unique user in the application.
- [Request ID](https://github.com/xepozz/request-id) - A simple library to generate both unique request and response IDs for tracing purposes.
- [AB](https://github.com/xepozz/ab) - A simple library to enable A/B testing based on a set of rules.
- [Shortcut](https://github.com/xepozz/shortcut) - Sets of helper functions for rapid development of Yii 3 applications.

 


