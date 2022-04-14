<?php

namespace Core\Domain\Entity;

use Core\Domain\Enum\Rating;
use Core\Domain\ValueObject\Uuid;

class Video
{
    public function __construct(
        protected Uuid $id,
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected bool $published = false,
    ) {
        
    }
}
