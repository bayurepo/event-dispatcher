<?php

namespace Gandung\EventDispatcher;

class EventDispatcherFactory
{
    /**
     * Get event dispatcher instance.
     *
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        return new EventDispatcher(new EventContainer);
    }
}
