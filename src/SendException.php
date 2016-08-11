<?php

namespace MVStudio\Telegraph;

class SendException extends \Exception
{
    protected $reason = null;

    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    private function setReason($reason) {
        $this->reason = $reason;
        return $this;
    }

    public function getReason() {
        return $this->reason;
    }

    static public function fromJSON($json) {
        $message = isset($json->message) ? json_encode($json->message) : null;
        $code = isset($json->code) ? $json->code : null;
        $reason = isset($json->reason) ? $json->reason : null;

        $exception = new SendException($message, $code);
        $exception->setReason($reason);
        return $exception;
    }
}
