<?php

namespace Gandung\EventDispatcher\Tests\Fixtures;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommonSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'event.generic' => [
                ['dummyResolver1', 10],
                ['dummyResolver2', 20],
                ['dummyResolver3', -90]
            ]
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
}
