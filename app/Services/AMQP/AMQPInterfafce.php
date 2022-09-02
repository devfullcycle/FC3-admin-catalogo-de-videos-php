<?php

namespace App\Services\AMQP;

use Closure;

interface AMQPInterface
{
    public function producer(string $queue, array $payload, string $exchange): void;
    public function producerFanout(array $payload, string $exchange): void;
    public function consumer(string $queue, string $exchange, Closure $callback): void;
}
