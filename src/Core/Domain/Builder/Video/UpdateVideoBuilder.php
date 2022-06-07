<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class UpdateVideoBuilder extends BuilderVideo
{
    public function createEntity(object $input): Builder
    {
        $this->entity = new Entity(
            id: new Uuid($input->id),
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
            createdAt: new DateTime($input->createdAt),
        );

        $this->addIds($input);

        return $this;
    }
}
