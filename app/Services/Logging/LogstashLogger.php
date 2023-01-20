<?php

namespace App\Services\Logging;

use Monolog\Formatter\LogstashFormatter;
use Monolog\Handler\SocketHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class LogstashLogger
{
    public function __invoke(array $config): LoggerInterface
    {
        $handler = new SocketHandler("udp://{$config['host']}:{$config['port']}");
        $handler->setFormatter(new LogstashFormatter(config('app.name')));

        return new Logger('logstash.main', [$handler]);
    }
}
