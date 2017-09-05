<?php

namespace Gandung\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventContainer
     */
    private $container;

    public function __construct(EventContainer $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function attachListener($event, $listener, $priority = 0)
    {
        $q = new Listener;
        $q->setListener($listener);
        $q->setPriority($priority);

        $this->container[$event] = $q;
    }

    /**
     * {@inheritdoc}
     */
    public function detachListener($event, $listener)
    {
        if (!isset($this->container[$event])) {
            return;
        }

        $current = $this->container[$event];

        unset($this->container[$event]);

        foreach ($current as $i => $v) {
            if ($v->getListener() !== $listener) {
                $this->container[$event] = $v;

                return;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners($event = null)
    {
        return $event === null
            ? $this->container
            : ($this->hasListeners($event)
                ? $this->container[$event]
                : null);
    }

    /**
     * {@inheritdoc}
     */
    public function hasListeners($event = null)
    {
        if ($event !== null) {
            return !empty($this->container[$event]);
        }

        return $this->container->hasAnyListeners();
    }

    /**
     * {@inheritdoc}
     */
    public function setListenerPriority($event, $listener, $priority)
    {
        if (!$this->hasListeners($event)) {
            return;
        }

        $current = $this->container[$event];
        $this->detachListener($event, $listener);
        $q = new Listener;
        $q->setListener($listener);
        $q->setPriority($priority);
        $this->container[$event] = $q;
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerPriority($event, $listener)
    {
        $current = $this->container[$event];

        foreach ($current as $i => $v) {
            if ($v->getListener() === $listener) {
                return $v->getPriority();
            }
        }

        return $current->getPriority();
    }

    /**
     * {@inheritdoc}
     */
    public function attachSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $event => $q) {
            if (is_string($q)) {
                $this->attachListener($event, [$subscriber, $q]);
            } elseif (is_string($q[0])) {
                $this->attachListener($event, [$subscriber, $q[0]], isset($q[1]) && is_int($q[1])
                    ? $q[1] : 0);
            } else {
                foreach ($q as $listener) {
                    $this->attachListener(
                        $event,
                        [$subscriber, $listener[0]],
                        isset($listener[1]) && is_int($listener[1])
                            ? $listener[1]
                            : 0
                    );
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detachSubscriber(EventSubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $event => $q) {
            if (is_array($q) && is_array($q[0])) {
                foreach ($q as $listener) {
                    $this->detachListener($event, [$subscriber, $q[0]]);
                }
            } else {
                $this->detachListener($event, [$subscriber, is_string($q) ? $q : $q[0]]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($event, Event $eventHandler = null)
    {
        if ($eventHandler === null) {
            $eventHandler = new Event;
        }

        $current = $this->container[$event];

        if (is_array($current)) {
            foreach ($current as $i => $v) {
                $v->dispatch($eventHandler);
            }
        } else {
            $this->container[$event]->dispatch($eventHandler);
        }

        return $eventHandler;
    }
}
