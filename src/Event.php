<?php

namespace Gandung\EventDispatcher;

class Event
{
    /**
     * @var boolean
     */
    private $propagationStopped = false;

    /**
     * Stop event propagation.
     */
    public function stopPropagation()
    {
        $this->propagationStopped = true;
    }

    /**
     * Determine if event propagation has been stopped.
     */
    public function isPropagationStopped()
    {
        return $this->propagationStopped;
    }
}
