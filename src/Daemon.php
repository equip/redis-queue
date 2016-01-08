<?php

namespace Equip\RedisQueue;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;

class Daemon
{
    /**
     * @var Consumer
     */
    private $consumer;

    /**
     * @var Stdio
     */
    private $stdio;

    /**
     * @var Context
     */
    private $context;

    /**
     * @var callable
     */
    private $listener;

    /**
     * @param Consumer $consumer
     * @param Stdio $stdio
     * @param Context $context
     */
    public function __construct(
        Consumer $consumer,
        Stdio $stdio,
        Context $context
    )
    {
        $this->consumer = $consumer;
        $this->stdio = $stdio;
        $this->context = $context;

        $this->setListener(function () { return true; });
    }

    /**
     * @param callable $listener
     */
    public function setListener(callable $listener)
    {
        $this->listener = $listener;
    }

    /**
     * @return int Status code to exit with
     */
    public function run()
    {
        $queue = $this->context->argv->get(1);
        if (!$queue) {
            $this->stdio->outln('<<red>>No queue specified<<reset>>');
            return Status::USAGE;
        }

        while (call_user_func($this->listener)) {
            $this->consumer->consume($queue);
        }
        return Status::SUCCESS;
    }
}
