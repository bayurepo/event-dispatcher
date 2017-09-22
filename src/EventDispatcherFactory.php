<?php

namespace Gandung\EventDispatcher;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
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
