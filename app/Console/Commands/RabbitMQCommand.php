<?php

namespace App\Console\Commands;

use App\Services\AMQP\AMQPInterface;
use Core\UseCase\Video\ChangeEncoded\ChangeEncodedPathVideo;
use Core\UseCase\Video\ChangeEncoded\DTO\ChangeEncodedVideoDTO;
use Illuminate\Console\Command;

class RabbitMQCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consumer RabbitMQ';

    public function __construct(
        private AMQPInterface $amqp,
        private ChangeEncodedPathVideo $useCase,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $closure = function ($message) {
            $body = json_decode($message->body);

            if (isset($body->Error) && $body->Error === '') {
                $encodedPath = $body->video->encoded_video_folder.'/stream.mpd';
                $videoId = $body->video->resource_id;

                $this->useCase->exec(
                    new ChangeEncodedVideoDTO(
                        id: $videoId,
                        encodedPath: $encodedPath,
                    )
                );
            }
        };

        $this->amqp->consumer(
            queue: config('microservices.queue_name'),
            exchange: config('microservices.micro_encoder_go.exchange_producer'),
            callback: $closure
        );

        return 0;
    }
}
