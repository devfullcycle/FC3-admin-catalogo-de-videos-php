<?php

namespace Core\UseCase\Video\Update;

use Core\Domain\Builder\Video\{
    Builder,
    UpdateVideoBuilder
};
use Core\UseCase\Video\BaseVideoUseCase;

class UpdateVideoUseCase extends BaseVideoUseCase
{
    protected function getBuilder(): Builder
    {
        return new UpdateVideoBuilder;
    }
}
