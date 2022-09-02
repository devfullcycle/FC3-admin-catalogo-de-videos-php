<?php

namespace Core\UseCase\Video\ChangeEncoded\DTO;

class ChangeEncodedVideoDTO
{
    public function __construct(
        public string $id,
        public string $encodedPath,
    ) {}
}
