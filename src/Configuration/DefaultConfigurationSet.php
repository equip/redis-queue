<?php

namespace Equip\RedisQueue\Configuration;

use Equip\Configuration\AurynConfiguration;
use Equip\Configuration\ConfigurationSet;
use Equip\Configuration\EnvConfiguration;

class DefaultConfigurationSet extends ConfigurationSet
{
    public function __construct(array $data = [])
    {
        $data = array_merge([
            EnvConfiguration::class,
            AurynConfiguration::class,
            AuraCliConfiguration::class,
            PredisConfiguration::class,
        ], $data);

        parent::__construct($data);
    }
}
