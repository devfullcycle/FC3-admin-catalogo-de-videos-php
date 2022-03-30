<?php

namespace App\Repositories\Eloquent;

use App\Models\Genre as Model;
use Core\Domain\Entity\Genre as Entity;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class GenreEloquentRepository implements GenreRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $genre): Entity
    {

    }
    
    public function findById(string $genreId): Entity
    {

    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }
    
    public function update(Entity $genre): Entity
    {

    }
    
    public function delete(string $genreId): bool
    {

    }
}
