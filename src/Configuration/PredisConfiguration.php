<?php

namespace Equip\RedisQueue\Configuration;

use Aura\Cli\Context;
use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;
use Predis\Client;

class PredisConfiguration implements ConfigurationInterface
{
    /** 
     * @param Injector $injector
     */
    public function apply(Injector $injector)
    {
        $injector->delegate(Client::class, [$this, 'getClient']);
    }

    /**
     * @param Context $context
     */
    public function getClient(Context $context)
    {
        $env = $context->env;
        $host = $env->get('REDIS_HOST') ?: '127.0.0.1';
        $port = $env->get('REDIS_PORT') ?: 6379;
        return new Client([
            'scheme' => 'tcp',
            'host' => $host,
            'port' => $port,
        ]);
    }
}
