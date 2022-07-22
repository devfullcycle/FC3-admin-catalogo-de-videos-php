<?php

namespace Tests\Stubs;

use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class VideoEventStub implements VideoEventManagerInterface
{
    public function dispatch(object $event): void
    {
        //
    }
}
