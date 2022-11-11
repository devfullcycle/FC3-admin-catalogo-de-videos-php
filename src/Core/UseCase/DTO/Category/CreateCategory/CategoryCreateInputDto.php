<?php

namespace Core\UseCase\DTO\Category\CreateCategory;

class CategoryCreateInputDto
{
    public function __construct(
        public string $name,
        public string $description = '',
        public bool $isActive = true,
    ) {
    }
}
