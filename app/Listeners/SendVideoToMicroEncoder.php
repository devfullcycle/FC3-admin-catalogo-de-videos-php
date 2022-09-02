<?php

namespace App\Listeners;

use App\Services\AMQP\AMQPInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendVideoToMicroEncoder
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private AMQPInterface $amqp
    ) {}

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->amqp->producerFanout(
            payload: $event->getPayload(),
            exchange: config('microservices.micro_encoder_go.exchange')
        );
    }
}
