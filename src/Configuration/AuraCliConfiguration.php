<?php

namespace Equip\RedisQueue\Configuration;

use Aura\Cli\CliFactory;
use Aura\Cli\Context;
use Aura\Cli\Stdio;
use Auryn\Injector;
use Equip\Configuration\ConfigurationInterface;

class AuraCliConfiguration implements ConfigurationInterface
{
    public function apply(Injector $injector)
    {
        $injector->delegate(Stdio::class, [$this, 'getStdio']);
        $injector->delegate(Context::class, [$this, 'getContext']);
    }

    public function getStdio(CliFactory $cli)
    {
        return $cli->newStdio();
    }

    public function getContext(CliFactory $cli)
    {
        return $cli->newContext($GLOBALS);
    }
}
