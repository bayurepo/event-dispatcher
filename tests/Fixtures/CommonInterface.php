<?php

namespace Gandung\EventDispatcher\Tests\Fixtures;

interface CommonInterface
{
    /**
     * @FixtureMethod(method="dummyResolver1")
     */
    public function dummyResolver1();

    /**
     * @FixtureMethod(method="dummyResolver2")
     */
    public function dummyResolver2();

    /**
     * @FixtureMethod(method="dummyResolver3")
     */
    public function dummyResolver3();
}
