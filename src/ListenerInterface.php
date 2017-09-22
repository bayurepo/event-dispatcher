<?php

namespace Gandung\EventDispatcher;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface ListenerInterface
{
    /**
     * Set listener.
     *
     * @param \Closure|array $listener The listener
     * @return void
     */
    public function setListener($listener);

    /**
     * Set listener priority.
     *
     * @param integer $priority The priority
     * @return void
     */
    public function setPriority($priority);

    /**
     * Get listener.
     *
     * @return \Closure|array
     */
    public function getListener();

    /**
     * Get listener priority.
     *
     * @return integer
     */
    public function getPriority();
    
    /**
     * Dispatch listener.
     */
    public function dispatch(Event $eventHandler);
}
