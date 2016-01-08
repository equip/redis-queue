<?php

namespace EquipTests\RedisQueu\Configuration;

use Auryn\Injector;
use Phake;
use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\ConfigurationInterface;
use Equip\Configuration\EnvConfiguration;
use Equip\RedisQueue\Configuration\AuraCliConfiguration;
use Equip\RedisQueue\Configuration\DefaultConfigurationSet;
use Equip\RedisQueue\Configuration\PredisConfiguration;

class DefaultConfigurationSetTest extends \PHPUnit_Framework_TestCase
{
    public function testApply()
    {
        $mock = Phake::mock(ConfigurationInterface::class);
        $class = get_class($mock);
        $configuration = new DefaultConfigurationSet([$class]);

        foreach ([
            EnvConfiguration::class,
            AurynConfiguration::class,
            AuraCliConfiguration::class,
            PredisConfiguration::class,
            $class,
        ] as $value) {
            $this->assertTrue($configuration->hasValue($value));
        }
    }
}
