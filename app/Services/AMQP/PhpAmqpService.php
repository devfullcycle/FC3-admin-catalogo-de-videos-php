<?php

namespace App\Services\AMQP;

use Closure;

class PhpAmqpService implements AMQPInterface
{
    public function producer(string $queue, array $payload, string $exchange): void
    {

    }

    public function producerFanout(string $queue, array $payload, string $exchange): void
    {

    }

    public function consumer(string $queue, string $exchange, Closure $callback): void
    {

    }    
}
