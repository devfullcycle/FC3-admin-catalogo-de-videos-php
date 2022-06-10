<?php

namespace Core\UseCase\Video\List\DTO;

use Core\Domain\Enum\Rating;

class ListOutputVideoUseCase
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $yearLaunched,
        public int $duration,
        public bool $opened,
        public Rating $rating,
        public array $categories = [],
        public array $genres = [],
        public array $castMembers = [],
        public ?string $videoFile = null,
        public ?string $trailerFile = null,
        public ?string $thumbFile = null,
        public ?string $thumbHalf = null,
        public ?string $bannerFile = null,
    ) {}
}
