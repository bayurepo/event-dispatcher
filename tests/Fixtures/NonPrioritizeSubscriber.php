<?php

namespace Gandung\EventDispatcher\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NonPrioritizeSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'event.generic.unprioritized' => [
                'dummyResolver1',
                'dummyResolver2',
                'dummyResolver3'
            ],
            'event.generic.single' => 'singleDummyResolver'
        ];
    }

    public function dummyResolver1()
    {
        echo sprintf("Inside {%s}@%s\n", \spl_object_hash($this), __METHOD__);
    }

    public function dummyResolver2()
    {
        echo sprintf("Inside {%s}@%s\n", \spl_object_hash($this), __METHOD__);
    }

    public function dummyResolver3()
    {
        echo sprintf("Inside {%s}@%s\n", \spl_object_hash($this), __METHOD__);
    }

    public function singleDummyResolver()
    {
        echo sprintf("Inside {%s}@%s\n", \spl_object_hash($this), __METHOD__);
    }
}
