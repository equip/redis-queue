<?php

namespace EquipTests\RedisQueue;

use Equip\Command\CommandInterface;
use Equip\RedisQueue\Consumer;
use Phake;
use Predis\Client;
use Relay\ResolverInterface;

class ConsumerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ResolverInterface
     */
    private $resolver;

    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var string
     */
    private $queue = 'queue';

    protected function setUp()
    {
        $this->client = Phake::mock(Client::class);
        $this->resolver = Phake::mock(ResolverInterface::class);
        $this->consumer = new Consumer($this->client, $this->resolver);
    }

    public function testConsumeWithNoJob()
    {
        $this->consumer->consume($this->queue);
        Phake::verify($this->client)->lpop($this->queue);
        Phake::verify($this->resolver, Phake::never())->__invoke(Phake::anyParameters());
    }

    public function testConsumeWithJob()
    {
        $command = Phake::mock(CommandInterface::class);
        $class = get_class($command);
        $options = ['foo' => 'bar'];
        $job = json_encode(['command' => $class, 'options' => $options]);

        Phake::when($command)->withOptions(Phake::anyParameters())->thenReturn($command);
        Phake::when($this->client)->lpop($this->queue)->thenReturn($job);
        Phake::when($this->resolver)->__invoke($class)->thenReturn($command);

        $this->consumer->consume($this->queue);

        Phake::verify($this->client)->lpop($this->queue);
        Phake::verify($command)->withOptions($options);
        Phake::verify($command)->execute();
    }
}
