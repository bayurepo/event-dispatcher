<?php

namespace Gandung\EventDispatcher\Tests;

use Gandung\EventDispatcher\EventDispatcher;
use Gandung\EventDispatcher\EventDispatcherFactory;

class EventDispatcherFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanGetInstance()
    {
        $factory = new EventDispatcherFactory();
        $this->assertInstanceOf(EventDispatcherFactory::class, $factory);
    }

    public function testCanGetEventDispatcher()
    {
        $factory = new EventDispatcherFactory();
        $this->assertInstanceOf(EventDispatcher::class, $factory->getDispatcher());
    }
}
