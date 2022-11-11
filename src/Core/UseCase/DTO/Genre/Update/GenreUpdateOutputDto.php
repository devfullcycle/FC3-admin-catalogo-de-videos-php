<?php

namespace Core\UseCase\DTO\Genre\Update;

class GenreUpdateOutputDto
{
    public function __construct(
        public string $id,
        public string $name,
        public bool $is_active,
        public string $created_at = '',
    ) {
    }
}
