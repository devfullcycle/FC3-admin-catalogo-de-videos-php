<?php

namespace Core\UseCase\DTO\Genre;

class GenreInputDto
{
    public function __construct(
        public string $id = '',
    ) {}
}
