<?php

namespace Gandung\EventDispatcher;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class EventContainer implements \ArrayAccess
{
    /**
     * @var array
     */
    private $eventStack = [];

    /**
     * {@inheritdoc}
     */
    public function offsetSet($event, $listener)
    {
        if (!($listener instanceof ListenerInterface)) {
            throw new \InvalidArgumentException(
                sprintf("Parameter 1 of %s require an instance of 'ListenerInterface'", __METHOD__)
            );
        }

        if (!isset($this->eventStack[$event])) {
            $this->eventStack[$event] = $listener;
        } else {
            if (is_array($this->eventStack[$event])) {
                $this->eventStack[$event][] = $listener;
            } else {
                $this->eventStack[$event] = [$this->eventStack[$event], $listener];
            }
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($event)
    {
        return $this->eventStack[$event];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($event)
    {
        return isset($this->eventStack[$event]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($event)
    {
        unset($this->eventStack[$event]);
    }

    /**
     * Determine if any listener has exists.
     */
    public function hasAnyListeners()
    {
        foreach ($this->eventStack as $listeners) {
            if ($listeners) {
                return true;
            }
        }

        return false;
    }
}
