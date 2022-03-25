<?php

namespace Core\UseCase\DTO\Genre\Delete;

class DeleteGenreOutputDto
{
    public function __construct(
        public bool $success
    ) {}
}
