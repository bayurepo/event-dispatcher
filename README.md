# Event Dispatcher

This is a simple library for managing event (e.g, http-kernel event, exception event) which utilizes [Symfony event dispatcher class interface](http://api.symfony.com/master/Symfony/Component/EventDispatcher/EventDispatcherInterface.html) as event dispatcher object and [Symfony event subscriber class interface](http://api.symfony.com/master/Symfony/Component/EventDispatcher/EventSubscriberInterface.html) as event subscriber (or listener collection) for current event dispatcher object.

This event dispatcher library uses several design patterns for extendibility and maintainability:

- [Observer Pattern](https://en.wikipedia.org/wiki/Observer_pattern) to maintain state between observer (listener) and observable (event) object.
- [Mediator Pattern](https://en.wikipedia.org/wiki/Mediator_pattern) to make all things truly extensible.

# Table Of Content

- [Quick Start](#quick-start)
- [API](#api)
	- [Event Dispatcher Factory](#event-dispatcher-factory)

# Quick Start

## EventDispatcher object instantiation

```php
use Gandung\EventDispatcher\EventDispatcher;
use Gandung\EventDispatcher\EventContainer;

$dispatcher = new EventDispatcher(new EventContainer);
```

or, you can do it with a factory.

```php
use Gandung\EventDispatcher\EventDispatcherFactory;

$factory = new EventDispatcherFactory;
$dispatcher = $factory->getDispatcher();
```

## Resolving events using simple closure based listener

```php
use Gandung\EventDispatcher\EventDispatcherFactory;

$factory = new EventDispatcherFactory;
$dispatcher = $factory->getDispatcher();
$listener = function() {
	echo "i'am a closure based listener.\n";
};

$dispatcher->attachListener('event.simple.closure', $listener, 20);
$dispatcher->dispatch('event.simple.closure');
```

## Resolving events using simple object based listener

```php
use Gandung\EventDispatcher\EventDispatcherFactory;

class Foo
{
	public function dummyResolver()
	{
		echo sprintf("Inside {%s}@{%s}", \spl_object_hash($this), __METHOD__);
	}
}

$foo = new Foo();
$factory = new EventDispatcherFactory;
$dispatcher = $factory->getDispatcher();
$dispatcher->attachListener('event.simple.object', [$foo, 'dummyResolver'], 20);
$dispatcher->dispatch('event.simple.object');
```

## Resolving subscribed events

```php
use Gandung\EventDispatcher\EventDispatcherFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooSubscriber implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			'event.simple.prioritized' => [
				['dummyResolver1', 20],
				['dummyResolver2', 10],
				['dummyResolver3', -90]
			],
			'event.simple.unprioritized' => [
				'unprioritizedResolver'
			],
			'event.simple.single' => 'singleResolver'
		];
	}

	public function dummyResolver1()
	{
		echo sprintf("{%s@%s}\n", \spl_object_hash($this), __METHOD__);
	}

	public function dummyResolver2()
	{
		echo sprintf("{%s@%s}\n", \spl_object_hash($this), __METHOD__);
	}

	public function dummyResolver3()
	{
		echo sprintf("{%s@%s}\n", \spl_object_hash($this), __METHOD__);
	}

	public function unprioritizedResolver()
	{
		echo sprintf("{%s@%s}\n", \spl_object_hash($this), __METHOD__);
	}

	public function singleResolver()
	{
		echo sprintf("{%s@%s}\n", \spl_object_hash($this), __METHOD__);
	}
}

$subscriber = new FooSubscriber;
$factory = new EventDispatcherFactory();
$dispatcher->attachSubscriber($subscriber);
$dispatcher->dispatch('event.simple.prioritized');
$dispatcher->dispatch('event.simple.unprioritized');
$dispatcher->dispatch('event.simple.single');
```

# API

## EventDispatcherFactory

```getDispatcher()```

Return the EventDispatcher object instance.