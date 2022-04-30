<?php

namespace Core\Domain\Entity;

use Core\Domain\Notification\Notification;
use Exception;

abstract class Entity
{
    protected $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }

    public function __get($property)
    {
        if (isset($this->{$property}))
            return $this->{$property};

        $className = get_class($this);
        throw new Exception("Property {$property} not found in class {$className}");
    }

    public function id(): string
    {
        return (string) $this->id;
    }

    public function createdAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
