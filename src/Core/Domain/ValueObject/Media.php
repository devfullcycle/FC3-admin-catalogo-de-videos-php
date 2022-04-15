<?php

namespace Core\Domain\ValueObject;

use Core\Domain\Enum\MediaStatus;

class Media
{
    public function __construct(
        protected string $filePath,
        protected MediaStatus $mediaStatus,
        protected string $encodedPath = '',
    ) {}

    public function __get($property)
    {
        return $this->{$property};
    }
}
