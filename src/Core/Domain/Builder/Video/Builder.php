<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Entity;
use Core\Domain\Enum\MediaStatus;

interface Builder
{
    public function createEntity(object $input): void;
    public function addMediaVideo(string $path, MediaStatus $mediaStatus): void;
    public function addTrailer(string $path): void;
    public function addThumb(string $path): void;
    public function addThumbHalf(string $path): void;
    public function addBanner(string $path): void;
    public function getEntity(): Entity;
}
