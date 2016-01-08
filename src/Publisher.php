<?php

namespace Equip\RedisQueue;

use Equip\Command\CommandInterface;
use Predis\Client;

class Publisher
{
    /**
     * @var Client
     */
    private $client;

    /** 
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $queue Name of the queue to receive the job
     * @param string $command FQCN for Command class to be executed by the job
     * @param array $options Options for the Command class instance
     */
    public function publish($queue, $command, array $options = [])
    {
        if (!is_subclass_of($command, CommandInterface::class)) {
            throw new \RuntimeException('Class does not implement CommandInterface: ' . $command);
        }

        $job = json_encode(['command' => $command, 'options' => $options]);
        $this->client->rpush($queue, $job);
    }
}
