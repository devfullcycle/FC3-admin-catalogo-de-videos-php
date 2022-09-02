<?php

namespace App\Services\AMQP;

use Closure;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class PhpAmqpService implements AMQPInterface
{
    protected $connection = null;
    protected $channel = null;

    public function __construct()
    {
        if ($this->connection) {
            return;
        }

        $configs = config('microservices.rabbitmq.hosts')[0];
        $this->connection = new AMQPStreamConnection(
            host: $configs['host'],
            port: $configs['port'],
            user: $configs['user'],
            password: $configs['password'],
            vhost: $configs['vhost']
        );

        $this->channel = $this->connection->channel();
    }

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
