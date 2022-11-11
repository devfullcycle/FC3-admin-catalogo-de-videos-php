<?php

namespace Core\UseCase\DTO\Category;

class CategoryInputDto
{
    public function __construct(
        public string $id = '',
    ) {
    }
}
