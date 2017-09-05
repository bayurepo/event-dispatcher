<?php

namespace Gandung\EventDispatcher\Tests;

use Gandung\EventDispatcher\EventContainer;
use Gandung\EventDispatcher\Listener;

class EventContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanGetInstance()
    {
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanThrowExceptionWhileSettingValueOnContainer()
    {
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
        $container['dummy.event'] = 'this is an invalid value.';
    }

    public function testCanSetValueOnContainer()
    {
        $listener = new Listener;
        $this->assertInstanceOf(Listener::class, $listener);
        $listener->setListener(function () {
            echo "Inside a listener." . PHP_EOL;
        });
        $listener->setPriority(100);
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
        $container['dummy.event'] = $listener;
        $this->assertTrue(isset($container['dummy.event']));
    }

    public function testCanGetValueOnContainer()
    {
        $listener = new Listener;
        $this->assertInstanceOf(Listener::class, $listener);
        $listener->setListener(function () {
            echo "Inside a listener." . PHP_EOL;
        });
        $listener->setPriority(100);
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
        $container['dummy.event'] = $listener;
        $this->assertTrue(isset($container['dummy.event']));
        $this->assertInstanceOf(Listener::class, $container['dummy.event']);
    }

    public function testIfValueOnSpecifiedOffsetExists()
    {
        $listener = new Listener;
        $this->assertInstanceOf(Listener::class, $listener);
        $listener->setListener(function () {
            echo "Inside a listener." . PHP_EOL;
        });
        $listener->setPriority(100);
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
        $container['dummy.event'] = $listener;
        $this->assertTrue(isset($container['dummy.event']));
    }

    public function testCanRemoveValueFromContainer()
    {
        $listener = new Listener;
        $this->assertInstanceOf(Listener::class, $listener);
        $listener->setListener(function () {
            echo "Inside a listener." . PHP_EOL;
        });
        $listener->setPriority(100);
        $container = new EventContainer;
        $this->assertInstanceOf(EventContainer::class, $container);
        $container['dummy.event'] = $listener;
        $this->assertTrue(isset($container['dummy.event']));
        unset($container['dummy.event']);
        $this->assertFalse(isset($container['dummy.event']));
    }
}
