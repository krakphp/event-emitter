<?php

use Krak\EventEmitter;

class TestListener implements EventEmitter\EventListener
{
    public function handle($param) {
        $param->i = 5;
    }
}

describe('Krak EventEmitter', function() {
    describe('InvokeEventEmitter', function() {
        beforeEach(function() {
            $this->emitter = EventEmitter\emitter();
        });
        describe('->on', function() {
            it('adds a new event', function() {
                $this->emitter->on('event', 'listener');
                assert($this->emitter->listeners('event') == ['listener']);
            });
        });
        describe('->listeners', function() {
            it('returns the listeners for an event', function() {
                $this->emitter->on('event', 'listener');
                assert($this->emitter->listeners('event') == ['listener']);
            });
            it('returns an empty array if not event exists', function() {
                assert($this->emitter->listeners('event') == []);
            });
        });
        describe('->removeListener', function() {
            it('removes a registered listener', function() {
                $this->emitter->on('event', 'a');
                $this->emitter->on('event', 'b');
                $this->emitter->on('event', 'c');
                $this->emitter->removeListener('event', 'b');
                assert($this->emitter->listeners('event') === ['a', 'c']);
            });
        });
        describe('->removeAllListeners', function() {
            it('removes all listeners if no event is given', function() {
                $this->emitter->on('event', 'a');
                $this->emitter->on('event1', 'b');
                $this->emitter->removeAllListeners();
                assert(!$this->emitter->listeners('event') && !$this->emitter->listeners('event1'));
            });
            it('removes all listeners for a given event', function() {
                $this->emitter->on('event', 'a');
                $this->emitter->on('event', 'b');
                $this->emitter->removeAllListeners('event');
                assert($this->emitter->listeners('event') === []);
            });
        });
        describe('emit', function() {
            it('fires the listeners for an event', function() {
                $this->emitter->on('event', function($param) {
                    $param->i += 1;
                });
                $this->emitter->on('event', function($param) {
                    $param->i += 2;
                });
                $param = new StdClass();
                $param->i = 0;
                $this->emitter->emit('event', $param);
                $this->emitter->emit('event', $param);
                assert($param->i == 6);
            });
            it('supports EventListener instances', function() {
                $this->emitter->on('event', new TestListener());
                $param = new StdClass();
                $this->emitter->emit('event', $param);
                assert($param->i == 5);
            });
        });
        describe('once', function() {
            it('adds a listener that will only be invoked once', function() {
                $this->emitter->on('event', function($param) {
                    $param->i = 5;
                });
                $this->emitter->once('event', function($param) {
                    $param->i = 1;
                });
                $param = new StdClass();
                $this->emitter->emit('event', $param);
                $this->emitter->emit('event', $param);
                assert($param->i == 5);
            });
        });
    });
});
