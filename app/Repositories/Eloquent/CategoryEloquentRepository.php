<?php

namespace App\Repositories\Eloquent;

use App\Models\Category as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\Category;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;

class CategoryEloquentRepository implements CategoryRepositoryInterface
{
    protected $model;

    public function __construct(Model $category)
    {
        $this->model = $category;
    }

    public function insert(Category $category): Category
    {
        $category = $this->model->create([
            'id' => $category->id(),
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
            'created_at' => $category->createdAt(),
        ]);

        return $this->toCategory($category);
    }

    public function findById(string $categoryId): Category
    {
        if (!$category = $this->model->find($categoryId)) {
            throw new NotFoundException('Category Not Found');
        }

        return $this->toCategory($category);
    }

    public function getIdsListIds(array $categoriesId = []): array
    {
        return $this->model
                    ->whereIn('id', $categoriesId)
                    ->get()
                    ->pluck('id');
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $categories = $this->model
                            ->where(function ($query) use ($filter) {
                                if ($filter)
                                    $query->where('name', 'LIKE', "%{$filter}%");
                            })
                            ->orderBy('id', $order)
                            ->get();

        return $categories->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $query = $this->model;
        if ($filter) {
            $query->where('name', 'LIKE', "%{$filter}%");
        }
        $query->orderBy('id', $order);
        $paginator = $query->paginate();

        return new PaginationPresenter($paginator);
    }

    public function update(Category $category): Category
    {
        if (!$categoryDb = $this->model->find($category->id())) {
            throw new NotFoundException('Category Not Found');
        }

        $categoryDb->update([
            'name' => $category->name,
            'description' => $category->description,
            'is_active' => $category->isActive,
        ]);

        $categoryDb->refresh();

        return $this->toCategory($categoryDb);
    }

    public function delete(string $categoryId): bool
    {
        if (!$categoryDb = $this->model->find($categoryId)) {
            throw new NotFoundException('Category Not Found');
        }
        
        return $categoryDb->delete();
    }

    private function toCategory(object $object): Category
    {
        $entity = new Category(
            id: $object->id,
            name: $object->name,
            description: $object->description,
        );
        ((bool) $object->is_active) ? $entity->activate() : $entity->disable();

        return $entity;
    }

}