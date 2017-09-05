<?php

namespace Gandung\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface EventDispatcherInterface
{
    /**
     * Append event listener to event listener stack.
     *
     * @param string $event Event id
     * @param \Closure|callable $listener Listener to the specified event id
     * @param integer $priority Priority to the specified event id
     * @return void
     */
    public function attachListener($event, $listener, $priority = 0);

    /**
     * Remove event listener from event listener stack.
     *
     * @param string $event Event id
     * @param \Closure|callable $listener Listener to the specified event id
     * @return void
     */
    public function detachListener($event, $listener);

    /**
     * Set event listener priority.
     *
     * @param string $event Event id
     * @param \Closure|callable $listener Listener to the specified event id
     * @param integer $priority Priority to the specified event id
     * @return void
     */
    public function setListenerPriority($event, $listener, $priority);

    /**
     * Get event listener priority.
     *
     * @param string $event Event id
     * @param \Closure|callable $listener Listener to the specified event id
     * @return integer|null
     */
    public function getListenerPriority($event, $listener);

    /**
     * Append an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber The event subscriber
     */
    public function attachSubscriber(EventSubscriberInterface $subscriber);

    /**
     * Remove an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber The event subscriber
     */
    public function detachSubscriber(EventSubscriberInterface $subscriber);
    
    /**
     * Dispatch jobs/listener based on current event id.
     *
     * @param string $event Event id
     * @return mixed
     */
    public function dispatch($event);
}
