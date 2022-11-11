<?php

namespace Core\UseCase\DTO\Category\DeleteCategory;

class CategoryDeleteOutputDto
{
    public function __construct(
        public bool $success
    ) {
    }
}
