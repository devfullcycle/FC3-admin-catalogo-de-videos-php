<?php

namespace Core\UseCase\Video\Update\DTO;

class UpdateInputVideoDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public array $categories,
        public array $genres,
        public array $castMembers,
        public ?array $videoFile = null,
        public ?array $trailerFile = null,
        public ?array $thumbFile = null,
        public ?array $thumbHalf = null,
        public ?array $bannerFile = null,
    ) {}
}
