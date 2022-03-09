<?php

namespace App\Repositories\Presenters;

use Core\Domain\Repository\PaginationInterface;

class PaginationPresenter implements PaginationInterface
{
    public function items(): array
    {
        return [];
    }

    public function total(): int
    {
        return 1;
    }

    public function lastPage(): int
    {
        return 1;
    }

    public function firstPage(): int
    {
        return 1;
    }

    public function currentPage(): int
    {
        return 1;
    }

    public function perPage(): int
    {
        return 1;
    }

    public function to(): int
    {
        return 1;
    }

    public function from(): int
    {
        return 1;
    }

}
