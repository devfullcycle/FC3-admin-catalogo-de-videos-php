<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;

class Video
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
    ) {
        $this->id = $this->id ?? Uuid::random();
    }
}
