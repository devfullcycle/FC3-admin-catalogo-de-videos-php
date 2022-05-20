<?php

namespace Core\UseCase\Video\Create\DTO;

use Core\Domain\Enum\Rating;

class CreateInputVideoDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public Rating $rating,
        public array $categories,
        public array $genres,
        public array $castMembers,
        public ?array $videoFile = null,
    ) {}
}
