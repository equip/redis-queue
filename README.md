# Abandoned

This library has been superseded by: [equip/queue](https://github.com/equip/queue)

# Equip Redis Queue

[![Latest Stable Version](https://img.shields.io/packagist/v/equip/redis-queue.svg)](https://packagist.org/packages/equip/redis-queue)
[![License](https://img.shields.io/packagist/l/equip/redis-queue.svg)](https://github.com/equip/redis-queue/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/equip/redis-queue.svg)](https://travis-ci.org/equip/redis-queue)
[![Code Coverage](https://scrutinizer-ci.com/g/equip/redis-queue/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/equip/redis-queue/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/equip/redis-queue/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/equip/redis-queue/?branch=master)

A small library for using [Redis](http://redis.io) as a job queue in [Equip](http://equipframework.readthedocs.org) applications.

## Installation

Use [Composer](https://getcomposer.org/).

```
composer require equip/redis-queue
```

[Add](http://equipframework.readthedocs.org/en/latest/#configuration) the [`DefaultConfigurationSet`](https://github.com/equip/redis-queue/blob/master/src/Configuration/DefaultConfigurationSet.php) configuration to your project.

## Consuming

Consumers are written as commands using [equip/command](https://github.com/equip/command). See [its documentation](http://equipframework.readthedocs.org/en/latest/commands/) for more information.

To run consumers, use a runner like the [example](https://github.com/equip/redis-queue/blob/master/bin/redis-consumer) included in this repository. This runner uses two environmental variables, `REDIS_HOST` and `REDIS_PORT`, to point to the Redis server to use; they default to `'127.0.0.1'` and `6379`, respectively. The runner takes a single required parameter: the Redis key representing the queue from which the consumer is to retrieve jobs.

```
REDIS_HOST=example.com REDIS_PORT=12345 ./bin/consume queue_name
```

Note that your runner will need to [configure](http://equipframework.readthedocs.org/en/latest/#configuration) your Auryn `Injector` instance appropriately for it to be able to create instances of your consumer command classes and their dependencies.

## Publishing

Jobs are published using an instance of the [`Publisher`](https://github.com/equip/redis-queue/blob/master/src/Publisher.php) class. Configuration included in [`DefaultConfigurationSet`](https://github.com/equip/redis-queue/blob/master/src/Configuration/DefaultConfigurationSet.php) should be sufficient to have Auryn generate an instance of it.

Here's an example of publishing a job from a [domain class](http://equipframework.readthedocs.org/en/latest/#domains), where `Acme\Command\FooCommand` is a command class intended to function as a consumer.

```php
namespace Acme;

use Acme\Command\FooCommand;
use Equip\Adr\DomainInterface;
use Equip\RedisQueue\Publisher;

class FooDomain implements DomainInterface
{
	private $publisher;

    public function __construct(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    public function __invoke(array $input)
    {
        // ...

        $command_options = ['foo' => 'bar'];
        $this->publisher->publish(
            'queue_name',
            FooCommand::class,
            $command_options
        );
    }
}
```

To publish a job, the `publish()` method of the `Publisher` instance is invoked with these arguments:

* The first argument is a string containing the name of the queue, which must be a valid [Redis key](http://redis.io/topics/data-types-intro#redis-keys)
* The second argument is a string containing the fully-qualified name of a command class containing the logic for the job to execute
* The third argument is an associative array of options to be used by an instance of the command class
