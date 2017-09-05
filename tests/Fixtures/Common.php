<?php

namespace Gandung\EventDispatcher\Tests\Fixtures;

class Common implements CommonInterface
{
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
