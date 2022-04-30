<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;

interface ValidatorInterface
{
    public function validate(Entity $entity): void;
}
