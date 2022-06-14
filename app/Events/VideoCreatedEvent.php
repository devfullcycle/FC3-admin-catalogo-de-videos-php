<?php

namespace App\Events;

use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class VideoCreatedEvent implements VideoEventManagerInterface
{
    public function dispatch(object $event): void
    {

    }
}
