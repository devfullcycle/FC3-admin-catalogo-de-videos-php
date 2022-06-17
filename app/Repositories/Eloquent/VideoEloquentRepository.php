<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as Model;
use App\Repositories\Presenters\PaginationPresenter;
use Core\Domain\Entity\{
    Entity,
    Video as VideoEntity
};
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(Entity $entity): Entity
    {
        $entityDb = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObjectToEntity($entityDb);
    }

    public function findById(string $entityId): Entity
    {
        if (!$entityDb = $this->model->find($entityId)) {
            throw new NotFoundException('Video not found');
        }

        return $this->convertObjectToEntity($entityDb);
    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {
        $result = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('title', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('title', $order)
                        ->get();

        return $result->toArray();
    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {
        $result = $this->model
                        ->where(function ($query) use ($filter) {
                            if ($filter) {
                                $query->where('title', 'LIKE', "%{$filter}%");
                            }
                        })
                        ->orderBy('title', $order)
                        ->paginate($totalPage, ['*'], 'page', $page);

        return new PaginationPresenter($result);
    }

    public function update(Entity $entity): Entity
    {
        if (!$entityDb = $this->model->find($entity->id())) {
            throw new NotFoundException('Video not found');
        }

        $entityDb->update([
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating->value,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        $entityDb->refresh();

        $this->syncRelationships($entityDb, $entity);

        return $this->convertObjectToEntity($entityDb);
    }

    public function delete(string $entityId): bool
    {
        if (!$entityDb = $this->model->find($entityId)) {
            throw new NotFoundException('Video not found');
        }

        return $entityDb->delete();
    }

    public function updateMedia(Entity $entity): Entity
    {
        
    }

    protected function syncRelationships(Model $model, Entity $entity)
    {
        $model->categories()->sync($entity->categoriesId);
        $model->genres()->sync($entity->genresId);
        $model->castMembers()->sync($entity->castMemberIds);
    }

    protected function convertObjectToEntity(object $model): VideoEntity
    {
        $entity = new VideoEntity(
            id: new Uuid($model->id),
            title: $model->title,
            description: $model->description,
            yearLaunched: (int) $model->year_launched,
            rating: Rating::from($model->rating),
            duration: (bool) $model->duration,
            opened: $model->opened
        );

        foreach ($model->categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($model->genres as $genre) {
            $entity->addGenre($genre->id);
        }

        foreach ($model->castMembers as $castMember) {
            $entity->addCastMember($castMember->id);
        }

        return $entity;
    }
}
