# Event Emitter

Simple event emitter package with a lot of flexibility. Inspired by the Evenement library, EventEmitter provides a simple interface for adding and emitting events.

## Installation

Install with composer at `krak/event-emitter`

## Usage

```php
<?php

$emitter = Krak\EventEmitter\emitter(); // creates the default emitter instance
$emitter->on('event', function($arg) {
    echo "Hello $arg\n";
});
$emitter->emit('event', "World");
```

This is the event emitter interface:

```php
<?php

interface EventEmitter {
    public function on($event, $listener);
    public function once($event, $listener);
    public function removeListener($event, $listener);
    public function removeAllListeners($event = null);
    public function listeners($event);
    public function emit($event, ...$arguments);
}
```

### Event Listeners

You can add event listener classes by implementing the `EventListener` interface.

```php
<?php

use Krak\EventEmitter\EventListener;

class AcmeListener implements EventListener
{
    public function handle($param) {

    }
}
```

You can then add this into the emitter via:

```php
<?php

$emitter->on('event', new AcmeListener());
```

### Custom Invocation

The default emitter supports custom invocations via the [Krak\\Invoke](https://github.com/krakphp/invoke) library. You can easily pass in a custom invoker which provides flexibility with how your listeners will be invoked.

```php
<?php

use Krak\EventEmitter;

// this creates an emitter that will support container services as listeners
$invoke = new Krak\Invoke\ContainerInvoke(EventEmitter\emitterInvoke(), $container);
$emitter = Krak\EventEmitter\emitter($invoke);
$emitter->on('event', 'service_id');
$emitter->emit('event', 1);
```
