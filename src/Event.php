<?php

namespace Gandung\EventDispatcher;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
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
