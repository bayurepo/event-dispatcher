# Event Dispatcher

This is a simple library for managing event (e.g, http-kernel event, exception event) which utilizes [Symfony event dispatcher class interface](http://api.symfony.com/master/Symfony/Component/EventDispatcher/EventDispatcherInterface.html) as event dispatcher object and [Symfony event subscriber class interface](http://api.symfony.com/master/Symfony/Component/EventDispatcher/EventSubscriberInterface.html) as event subscriber (or listener collection) for current event dispatcher object.

This event dispatcher library uses several design patterns for extendibility and maintainability:

- [Observer Pattern](https://en.wikipedia.org/wiki/Observer_pattern) to maintain state between observer (listener) and observable (event) object.
- [Mediator Pattern](https://en.wikipedia.org/wiki/Mediator_pattern) to make all things truly extensible.