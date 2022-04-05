<?php

namespace Core\Domain\Entity;

use Core\Domain\ValueObject\Uuid;
use DateTime;

class CastMember
{
    public function __construct(
        protected ?Uuid $id = null,
        protected string $name,
        protected ?DateTime $createdAt = null,
    ) {
        
    }
}
