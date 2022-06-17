<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as Model;
use Core\Domain\Entity\{
    Entity,
    Video as VideoEntity
};
use Core\Domain\Enum\Rating;
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

        return $this->convertObjectToEntity($entityDb);
    }

    public function findById(string $entityId): Entity
    {

    }

    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }

    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }

    public function update(Entity $entity): Entity
    {

    }

    public function delete(string $entityId): bool
    {

    }

    public function updateMedia(Entity $entity): Entity
    {
        
    }

    protected function convertObjectToEntity(object $object): VideoEntity
    {
        return new VideoEntity(
            id: new Uuid($object->id),
            title: $object->title,
            description: $object->description,
            yearLaunched: (int) $object->year_launched,
            rating: Rating::from($object->rating),
            duration: (bool) $object->duration,
            opened: $object->opened
        );
    }
}
