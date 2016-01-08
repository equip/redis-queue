<?php

namespace EquipTests\RedisQueue;

use Equip\Command\CommandInterface;
use Equip\RedisQueue\Publisher;
use Phake;
use Predis\Client;

class PublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var string
     */
    private $queue = 'queue';

    protected function setUp()
    {
        $this->client = Phake::mock(Client::class);
        $this->publisher = new Publisher($this->client);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Class does not implement CommandInterface: \stdClass
     */
    public function testPublishWithInvalidCommand()
    {
        $this->publisher->publish($this->queue, '\stdClass');
    }

    public function testPublishValidCommand()
    {
        $command = Phake::mock(CommandInterface::class);
        $class = get_class($command);
        $options = ['foo' => 'bar'];

        $this->publisher->publish($this->queue, $class, $options);

        Phake::verify($this->client)->rpush($this->queue, Phake::capture($actual));
        $expected = json_encode(['command' => $class, 'options' => $options]);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
}
