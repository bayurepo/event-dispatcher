<?php

namespace Gandung\EventDispatcher;

class Listener implements ListenerInterface
{
    /**
     * @var \Closure|array
     */
    private $listener;

    /**
     * @var integer
     */
    private $priority;

    /**
     * {@inheritdoc}
     */
    public function setListener($listener)
    {
        $this->listener = $listener;
    }

    /**
     * {@inheritdoc}
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getListener()
    {
        return $this->listener;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(Event $eventHandler)
    {
        if ($eventHandler->isPropagationStopped()) {
            return;
        }

        if (is_array($this->listener)) {
            call_user_func([$this->listener[0], $this->listener[1]], $eventHandler);
        } else {
            call_user_func($this->listener, $eventHandler);
        }
    }
}
