<?php

namespace EquipTests\RedisQueue\Configuration;

use Auryn\Injector;
use Aura\Cli\Context;
use Aura\Cli\Stdio;
use Equip\RedisQueue\Configuration\AuraCliConfiguration;

class AuraCliConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function dataMapping()
    {
        return [
            [Stdio::class],
            [Context::class],
        ];
    }

    /**
     * @param string $class
     * @dataProvider dataMapping
     */
    public function testApply($class)
    {
        $injector = new Injector;
        $configuration = $injector->make(AuraCliConfiguration::class);
        $configuration->apply($injector);
        $instance = $injector->make($class);
        $this->assertInstanceOf($class, $instance);
    }
}
