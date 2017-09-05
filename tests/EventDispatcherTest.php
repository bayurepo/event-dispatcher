<?php

namespace Gandung\EventDispatcher\Tests;

use Gandung\EventDispatcher\EventDispatcher;
use Gandung\EventDispatcher\EventContainer;
use Gandung\EventDispatcher\Event;
use Gandung\EventDispatcher\Tests\Fixtures\Common;
use Gandung\EventDispatcher\Tests\Fixtures\CommonSubscriber;
use Gandung\EventDispatcher\Tests\Fixtures\NonPrioritizeSubscriber;

class EventDispatcherTest extends \PHPUnit_Framework_TestCase
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

    public function testCanGetInstance()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
    }

    public function testCanRegisterClosureRelatedListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $prio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
    }

    public function testCanRegisterObjectRelatedListeners()
    {
        $common = new Common;
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3'], -90);
        $prio = [
            $dispatcher->getListenerPriority('object.1', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.2', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.3', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
    }

    public function testCanRemoveClosureRelatedListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $prio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
        $dispatcher->detachListener('closure.1', $this->closureListFixtures[0]);
        $dispatcher->detachListener('closure.2', $this->closureListFixtures[1]);
        $dispatcher->detachListener('closure.3', $this->closureListFixtures[2]);
        $this->assertFalse($dispatcher->hasListeners('closure.1'));
        $this->assertFalse($dispatcher->hasListeners('closure.2'));
        $this->assertFalse($dispatcher->hasListeners('closure.3'));
    }

    public function testCanRemoveObjectRelatedListeners()
    {
        $common = new Common;
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3'], -90);
        $prio = [
            $dispatcher->getListenerPriority('object.1', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.2', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.3', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
        $dispatcher->detachListener('object.1', [$common, 'dummyResolver1']);
        $dispatcher->detachListener('object.2', [$common, 'dummyResolver2']);
        $dispatcher->detachListener('object.3', [$common, 'dummyResolver3']);
        $this->assertFalse($dispatcher->hasListeners('object.1'));
        $this->assertFalse($dispatcher->hasListeners('object.2'));
        $this->assertFalse($dispatcher->hasListeners('object.3'));
    }

    public function testCanRegisterMultipleClosureRelatedListenerOnSameEvent()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[2], -90);
        $prio = [
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[2])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
    }

    public function testCanRegisterMultipleObjectRelatedListenerOnSameEvent()
    {
        $common = new Common;
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver3'], -90);
        $prio = [
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
    }

    public function testCanRemoveMultipleClosureRelatedListenerOnSameEvent()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.grouping', $this->closureListFixtures[2], -90);
        $prio = [
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[0], 10),
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[1], 20),
            $dispatcher->getListenerPriority('closure.grouping', $this->closureListFixtures[2], -90)
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
        $dispatcher->detachListener('closure.grouping', $this->closureListFixtures[0]);
        $dispatcher->detachListener('closure.grouping', $this->closureListFixtures[1]);
        $dispatcher->detachListener('closure.grouping', $this->closureListFixtures[2]);
        $this->assertFalse($dispatcher->hasListeners('closure.grouping'));
    }

    public function testCanRemoveMultipleObjectRelatedListenerOnSameEvent()
    {
        $common = new Common;
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver1'], 10);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver2'], 20);
        $dispatcher->attachListener('object.grouping', [$common, 'dummyResolver3'], -90);
        $prio = [
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver1']),
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver2']),
            $dispatcher->getListenerPriority('object.grouping', [$common, 'dummyResolver3'])
        ];
        $this->assertInternalType('integer', $prio[0]);
        $this->assertEquals(10, $prio[0]);
        $this->assertInternalType('integer', $prio[1]);
        $this->assertEquals(20, $prio[1]);
        $this->assertInternalType('integer', $prio[2]);
        $this->assertEquals(-90, $prio[2]);
        $dispatcher->detachListener('object.grouping', [$common, 'dummyResolver1']);
        $dispatcher->detachListener('object.grouping', [$common, 'dummyResolver2']);
        $dispatcher->detachListener('object.grouping', [$common, 'dummyResolver3']);
        $this->assertFalse($dispatcher->hasListeners('object.grouping'));
    }

    public function testCanRegisterSubscriber()
    {
        $common = new CommonSubscriber;
        $this->assertInstanceOf(CommonSubscriber::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber($common);
        $listener = $dispatcher->getListeners('event.generic');
        $prio = [];
        $expectedPrio = [10, 20, -90];

        foreach ($listener as $k => $q) {
            $prio[] = $dispatcher->getListenerPriority('event.generic', $q->getListener());
        }

        foreach ($prio as $k => $p) {
            $this->assertInternalType('integer', $p);
            $this->assertEquals($expectedPrio[$k], $p);
        }
    }

    public function testCanRemoveSubscriber()
    {
        $common = new CommonSubscriber();
        $this->assertInstanceOf(CommonSubscriber::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber($common);
        $listener = $dispatcher->getListeners('event.generic');
        $prio = [];
        $expectedPrio = [10, 20, -90];

        foreach ($listener as $k => $q) {
            $prio[] = $dispatcher->getListenerPriority('event.generic', $q->getListener());
        }

        foreach ($prio as $k => $p) {
            $this->assertInternalType('integer', $p);
            $this->assertEquals($expectedPrio[$k], $p);
        }

        $dispatcher->detachSubscriber($common);
        $this->assertFalse($dispatcher->hasListeners('event.generic'));
    }

    public function testIfHasAnyListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $this->assertTrue($dispatcher->hasListeners());
    }

    public function testIfHasAnyListenersOnEmptyStack()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $this->assertFalse($dispatcher->hasListeners());
    }

    public function testCanResetPriorityOnCurrentEventListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0]);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1]);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2]);
        $expectedPrio = [10, 20, -90];
        $prio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];

        foreach ($prio as $k => $q) {
            $this->assertInternalType('integer', $q);
            $this->assertEquals(0, $q);
        }

        $dispatcher->setListenerPriority('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->setListenerPriority('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->setListenerPriority('closure.3', $this->closureListFixtures[2], -90);

        $prio = [
            $dispatcher->getListenerPriority('closure.1', $this->closureListFixtures[0]),
            $dispatcher->getListenerPriority('closure.2', $this->closureListFixtures[1]),
            $dispatcher->getListenerPriority('closure.3', $this->closureListFixtures[2])
        ];

        foreach ($prio as $k => $q) {
            $this->assertInternalType('integer', $q);
            $this->assertEquals($expectedPrio[$k], $q);
        }
    }

    public function testCanResetPriorityOnSameEventWithDifferentListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber(new CommonSubscriber);
        $listener = $dispatcher->getListeners('event.generic');
        $expectedPrio = [10, 20, -90];
        $prio = [];

        foreach ($listener as $k => $q) {
            $prio[] = $dispatcher->getListenerPriority('event.generic', $q->getListener());
        }

        foreach ($prio as $o => $y) {
            $this->assertInternalType('integer', $y);
            $this->assertEquals($expectedPrio[$o], $y);
        }

        $newPrio = [1337, 31337, 7373];
        $prio = [];

        foreach ($listener as $k => $q) {
            $prio[] = $dispatcher->setListenerPriority('event.generic', $q->getListener(), $newPrio[$k]);
        }
    }

    public function testCanResetPriorityOnNonexistentEvent()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->setListenerPriority('nonexistent.event', $this->closureListFixtures[0], 7373);
    }

    public function testCanAttachNonPrioritizeSubscriber()
    {
        $subscriber = new NonPrioritizeSubscriber;
        $this->assertInstanceOf(NonPrioritizeSubscriber::class, $subscriber);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber($subscriber);
        $listener = $dispatcher->getListeners('event.generic.unprioritized');
        $prio = [];

        foreach ($listener as $k => $q) {
            $prio[] = $dispatcher->getListenerPriority('event.generic.unprioritized', $q->getListener());
        }

        foreach ($prio as $o => $y) {
            $this->assertInternalType('integer', $y);
            $this->assertEquals(0, $y);
        }

        $dispatcher->detachSubscriber($subscriber);
        $this->assertFalse($dispatcher->hasListeners('event.generic.unprioritized'));
        $this->assertFalse($dispatcher->hasListeners('event.generic.single'));
    }

    public function testCanDispatchClosureRelatedListeners()
    {
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('closure.1', $this->closureListFixtures[0], 10);
        $dispatcher->attachListener('closure.2', $this->closureListFixtures[1], 20);
        $dispatcher->attachListener('closure.3', $this->closureListFixtures[2], -90);
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('closure.1'));
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('closure.2'));
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('closure.3'));
    }

    public function testCanDispatchObjectRelatedListeners()
    {
        $common = new Common;
        $this->assertInstanceOf(Common::class, $common);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachListener('object.1', [$common, 'dummyResolver1']);
        $dispatcher->attachListener('object.2', [$common, 'dummyResolver2']);
        $dispatcher->attachListener('object.3', [$common, 'dummyResolver3']);
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('object.1'));
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('object.2'));
        $this->assertInstanceOf(Event::class, $dispatcher->dispatch('object.3'));
    }

    public function testCanDispatchListenersFromRegisteredSubscriber()
    {
        $eventHandler = new Event;
        $subscriber = new CommonSubscriber;
        $this->assertInstanceOf(CommonSubscriber::class, $subscriber);
        $dispatcher = new EventDispatcher(new EventContainer);
        $this->assertInstanceOf(EventDispatcher::class, $dispatcher);
        $dispatcher->attachSubscriber($subscriber);
        $eventHandler->stopPropagation();
        $dispatcher->dispatch('event.generic', $eventHandler);
        $dispatcher->detachSubscriber($subscriber);
    }
}
