<?php

namespace EquipTests\RedisQueue;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Context\Argv;
use Aura\Cli\Status;
use Equip\RedisQueue\Consumer;
use Equip\RedisQueue\Daemon;
use Phake;

class DaemonTest extends \PHPUnit_Framework_TestCase
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
     * @var Argv
     */
    private $argv;

    /**
     * @var Daemon
     */
    private $daemon;

    /**
     * @var string
     */
    private $queue = 'queue';

    /**
     * @var boolean
     */
    private $listening = false;

    protected function setUp()
    {
        $this->consumer = Phake::mock(Consumer::class);
        $this->stdio = Phake::mock(Stdio::class);
        $this->argv = Phake::mock(Argv::class);
        $context = Phake::mock(Context::class);
        Phake::when($context)->__get('argv')->thenReturn($this->argv);
        $this->daemon = new Daemon(
            $this->consumer,
            $this->stdio,
            $context
        );
		$this->daemon->setListener(function () {
            return $this->listening = !$this->listening;
        });
    }

    public function testRunWithoutQueue()
    {
        $result = $this->daemon->run();
        $this->assertSame(Status::USAGE, $result);
        Phake::verify($this->stdio)->outln('<<red>>No queue specified<<reset>>');
    }

    public function testRunWithQueue()
    {
        Phake::when($this->argv)->get(1)->thenReturn($this->queue);
        $result = $this->daemon->run();
        $this->assertSame(Status::SUCCESS, $result);
        Phake::verify($this->consumer)->consume($this->queue);
    }
}
