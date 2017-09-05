<?php

namespace Gandung\EventDispatcher\Tests\Adapter;

use Gandung\EventDispatcher\Adapter\SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcher as ThirdPartyEventDispatcher;
use Gandung\EventDispatcher\Tests\Fixtures\Common;
use Gandung\EventDispatcher\Tests\Fixtures\CommonSubscriber;

class SymfonyEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $closureListFixtures;

    public function __construct()
    {
        $this->closureListFixtures = [
            function () {
                echo sprintf("Inside %s\n", md5(uniqid()));
            },
            function () {
                echo sprintf("Inside %s\n", md5(uniqid()));
            },
            function () {
                echo sprintf("Inside %s\n", md5(uniqid()));
            }
        ];
    }

    public function testCanGetInstanceOf()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
    }

    public function testCanRegisterClosureRelatedListener()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
    }

    public function testCanRemoveClosureRelationListener()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
        $dispatcher->detachListener('closure.1', $this->closureListFixtures[0]);
        $dispatcher->detachListener('closure.2', $this->closureListFixtures[1]);
        $dispatcher->detachListener('closure.3', $this->closureListFixtures[2]);
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('closure.1'));
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('closure.2'));
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('closure.3'));
    }

    public function testCanRegisterObjectRelatedListener()
    {
        $common = new Common();
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3'], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('object.1', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.2', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.3', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
    }

    public function testCanRemoveObjectRelatedListener()
    {
        $common = new Common();
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3'], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('object.1', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.2', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.3', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
        $dispatcher->detachListener('object.1', [$common, 'dummyResolver1']);
        $dispatcher->detachListener('object.2', [$common, 'dummyResolver2']);
        $dispatcher->detachListener('object.3', [$common, 'dummyResolver3']);
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('object.1'));
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('object.2'));
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('object.3'));
    }

    public function testCanSetListenerPriority()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0]);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1]);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2]);
        $listenerPrio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(0, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(0, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(0, $listenerPrio[2]);
        $dispatcher->setListenerPriority('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->setListenerPriority('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->setListenerPriority('closure.3', $this->closureListFixtures[2], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
    }

    public function testCanGetListenerPriority()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $listenerPrio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $listenerPrio[0]);
        $this->assertEquals(10, $listenerPrio[0]);
        $this->assertInternalType('integer', $listenerPrio[1]);
        $this->assertEquals(20, $listenerPrio[1]);
        $this->assertInternalType('integer', $listenerPrio[2]);
        $this->assertEquals(-90, $listenerPrio[2]);
    }

    public function testCanRegisterSubscriber()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber(new CommonSubscriber);
        $listener = $dispatcher->getAdapter()->getListeners('event.generic');
        $listenerPrio = [];
        $expectedPrio = [20, 10, -90];

        foreach ($listener as $k => $q) {
            $listenerPrio[] = $dispatcher->getListenerPriority('event.generic', $q);
        }

        foreach ($listenerPrio as $k => $prio) {
            $this->assertInternalType('integer', $prio);
            $this->assertEquals($expectedPrio[$k], $prio);
        }
    }

    public function testCanRemoveSubscriber()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber(new CommonSubscriber);
        $listener = $dispatcher->getAdapter()->getListeners('event.generic');
        $listenerPrio = [];
        $expectedPrio = [20, 10, -90];

        foreach ($listener as $k => $q) {
            $listenerPrio[] = $dispatcher->getListenerPriority('event.generic', $q);
        }

        foreach ($listenerPrio as $k => $prio) {
            $this->assertInternalType('integer', $prio);
            $this->assertEquals($expectedPrio[$k], $prio);
        }

        $dispatcher->detachSubscriber(new CommonSubscriber);
        $this->assertFalse($dispatcher->getAdapter()->hasListeners('event-generic'));
    }

    public function testCanDispatchClosureRelatedListeners()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('closure.1'));
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('closure.2'));
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('closure.3'));
        $dispatcher->dispatch('closure.1');
        $dispatcher->dispatch('closure.2');
        $dispatcher->dispatch('closure.3');
    }

    public function testCanDispatchObjectRelatedListeners()
    {
        $common = new Common;
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3'], -90);
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('object.1'));
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('object.2'));
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('object.3'));
        $dispatcher->dispatch('object.1');
        $dispatcher->dispatch('object.2');
        $dispatcher->dispatch('object.3');
    }

    public function testCanDispatchRegisteredSubscriber()
    {
        $dispatcher = new SymfonyEventDispatcher(new ThirdPartyEventDispatcher);
        $this->assertInstanceOf(SymfonyEventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber(new CommonSubscriber);
        $this->assertTrue($dispatcher->getAdapter()->hasListeners('event.generic'));
        $dispatcher->dispatch('event.generic');
    }
}
