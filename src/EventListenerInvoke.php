<?php

namespace Krak\EventEmitter;

use Krak\Invoke;

class EventListenerInvoke extends Invoke\InvokeDecorator
{
    public function invoke($func, ...$params) {
        if (!$func instanceof EventListener) {
            return $this->invoke->invoke($func, ...$params);
        }

        if (!method_exists($func, 'handle')) {
            throw new \LogicException('EventListener must have handle method');
        }

        return $this->invoke->invoke([$func, 'handle'], ...$params);
    }
}
