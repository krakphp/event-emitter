<?php

namespace Krak\EventEmitter;

use Krak\Invoke;

function emitter(Invoke\Invoke $invoke = null) {
    return new InvokeEventEmitter($invoke);
}

function emitterInvoke() {
    return new EventListenerInvoke(new Invoke\CallableInvoke());
}
