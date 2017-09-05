<?php

namespace Gandung\EventDispatcher\Adapter;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SymfonyEventDispatcher implements EventDispatcherAdapterInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function attachListener($event, $listener, $priority = 0)
    {
        $this->eventDispatcher->addListener($event, $listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function detachListener($event, $listener)
    {
        $this->eventDispatcher->removeListener($event, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function setListenerPriority($event, $listener, $priority)
    {
        if ($this->eventDispatcher->hasListeners($event)) {
            $current = $this->eventDispatcher->getListeners($event);
            
            foreach ($current as $key => $q) {
                if ($q === $listener) {
                    $this->eventDispatcher->removeListener($event, $q);
                    $this->eventDispatcher->addListener($event, $listener, $priority);

                    break;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerPriority($event, $listener)
    {
        return $this->eventDispatcher->getListenerPriority($event, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function attachSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->addSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function detachSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->eventDispatcher->removeSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($event)
    {
        return $this->eventDispatcher->dispatch($event);
    }

    public function getAdapter()
    {
        return $this->eventDispatcher;
    }
}
