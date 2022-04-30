<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;

class VideoLaravelValidator implements ValidatorInterface
{
    public function validate(Entity $entity): void
    {
        dd((array) $entity);
    }
}
