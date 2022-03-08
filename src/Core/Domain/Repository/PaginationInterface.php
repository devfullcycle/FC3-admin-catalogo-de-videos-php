<?php

namespace Core\Domain\Repository;

interface PaginationInterface
{
    /**
     * @return stdClass[]
     */
    public function items(): array;
    public function total(): int;
    public function lastPage(): int;
    public function firstPage(): int;
    public function currentPage(): int;
    public function perPage(): int;
    public function to(): int;
    public function from(): int;
}
