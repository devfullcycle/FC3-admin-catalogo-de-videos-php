<?php

namespace Core\UseCase\Interfaces;

interface TransactionInterface
{
    public function commit();

    public function rollback();
}
