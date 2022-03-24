<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Genre
{
    use MethodsMagicsTrait;

    public function __construct(
        protected string $name,
        protected ?Uuid $id = null,
        protected $isActive = true,
        protected ?DateTime $createdAt = null,
    ) {
        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validate();
    }

    public function activate()
    {
        $this->isActive = true;
    }

    public function deactivate()
    {
        $this->isActive = false;
    }

    public function update(string $name)
    {
        $this->name = $name;

        $this->validate();
    }

    protected function validate()
    {
        DomainValidation::strMaxLength($this->name);
        DomainValidation::strMinLength($this->name);
    }

}
