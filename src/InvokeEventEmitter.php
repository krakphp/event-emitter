<?php

namespace Krak\EventEmitter;

use Krak\Invoke;

class InvokeEventEmitter implements EventEmitter
{
    private $invoke;
    private $listeners;

    public function __construct(Invoke\Invoke $invoke = null) {
        $this->invoke = $invoke ?: emitterInvoke();
        $this->listeners = [];
    }

    public function on($event, $listener) {
        $this->listeners[$event][] = $listener;
    }
    public function once($event, $listener) {
        $once_listener = function(...$params) use (&$once_listener, $event, $listener) {
            $this->invoke->invoke($listener, ...$params);
            $this->removeListener($event, $once_listener);
        };
        $this->listeners[$event][] = $once_listener;
    }
    public function removeListener($event, $listener) {
        if (!isset($this->listeners[$event])) {
            return;
        }

        $idx = array_search($listener, $this->listeners[$event]);
        if ($idx === false) {
            return;
        }

        array_splice($this->listeners[$event], $idx, 1);
    }
    public function removeAllListeners($event = null) {
        if (!$event) {
            $this->listeners = [];
        } else {
            $this->listeners[$event] = [];
        }
    }
    public function listeners($event) {
        if (!isset($this->listeners[$event])) {
            return [];
        }

        return $this->listeners[$event];
    }

    public function emit($event, ...$arguments) {
        if (!isset($this->listeners[$event])) {
            return;
        }

        foreach ($this->listeners[$event] as $listener) {
            $this->invoke->invoke($listener, ...$arguments);
        }
    }
}
