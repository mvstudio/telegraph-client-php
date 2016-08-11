<?php

namespace MVStudio\Telegraph;

interface ClientInterface {
    public function send(array $options);
    public function sendTemplate(array $options);
}
