<?php

namespace Equip\RedisQueue;

use Predis\Client;
use Relay\ResolverInterface;

class Consumer
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
     * @param Client $client
     * @param ResolverInterface $resolver
     */
    public function __construct(Client $client, ResolverInterface $resolver)
    {
        $this->client = $client;
        $this->resolver = $resolver;
    }

    /**
     * @param string $queue
     */
    public function consume($queue)
    {
        $value = $this->client->lpop($queue);
        if ($value === null) {
            return;
        }

        $decoded = json_decode($value, true);
        $command = call_user_func($this->resolver, $decoded['command']);

        $command
            ->withOptions($decoded['options'])
            ->execute();
    }
}
