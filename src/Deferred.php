<?php

namespace React\Promise;

class Deferred implements PromisorInterface
{
    private $promise;
    public $resolveCallback;
    public $rejectCallback;
    public $notifyCallback;
    private $canceller;

    public function __construct($canceller = null)
    {
        $this->canceller = $canceller;
    }

    public function promise()
    {
        if (null === $this->promise) {
            $that = $this;
            $this->promise = new Promise(function ($resolve, $reject, $notify) use ($that) {
                $that->resolveCallback = $resolve;
                $that->rejectCallback  = $reject;
                $that->notifyCallback  = $notify;
            }, $this->canceller);
            $this->canceller = null;
        }

        return $this->promise;
    }

    public function resolve($value = null)
    {
        $this->promise();

        \call_user_func($this->resolveCallback, $value);
    }

    public function reject($reason = null)
    {
        $this->promise();

        \call_user_func($this->rejectCallback, $reason);
    }

    /**
     * @deprecated 2.6.0 Progress support is deprecated and should not be used anymore.
     * @param mixed $update
     */
    public function notify($update = null)
    {
        $this->promise();

        \call_user_func($this->notifyCallback, $update);
    }

    /**
     * @deprecated 2.2.0
     * @see Deferred::notify()
     */
    public function progress($update = null)
    {
        $this->notify($update);
    }
}
