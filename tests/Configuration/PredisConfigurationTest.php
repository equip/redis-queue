<?php

namespace EquipTests\RedisQueue\Configuration;

use Auryn\Injector;
use Equip\RedisQueue\Configuration\PredisConfiguration;
use Predis\Client;

class PredisConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $injector = new Injector;
        $configuration = $injector->make(PredisConfiguration::class);
        $configuration->apply($injector);
        $instance = $injector->make(Client::class);
        $this->assertInstanceOf(Client::class, $instance);
    }
}
