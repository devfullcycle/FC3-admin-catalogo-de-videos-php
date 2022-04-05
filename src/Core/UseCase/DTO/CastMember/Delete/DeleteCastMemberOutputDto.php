<?php

namespace Core\UseCase\DTO\CastMember\Delete;

class DeleteCastMemberOutputDto
{
    public function __construct(
        public bool $success
    ) {}
}
