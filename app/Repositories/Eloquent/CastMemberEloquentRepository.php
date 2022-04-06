<?php

namespace App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;

class CastMemberEloquentRepository implements CastMemberRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function insert(CastMember $castMember): CastMember
    {
        $dataDb = $this->model->create([
            'id' => $castMember->id(),
            'name' => $castMember->name,
            'type' => $castMember->type->value,
            'created_at' => $castMember->createdAt(),
        ]);

        return $this->convertToEntity($dataDb);
    }
    
    public function findById(string $castMemberId): CastMember
    {
        if (!$dataDb = $this->model->find($castMemberId)) {
            throw new NotFoundException("Cast Member {$castMemberId} Not Found");
        }

        return $this->convertToEntity($dataDb);
    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, int $totalPage = 15): PaginationInterface
    {

    }
    
    public function update(CastMember $castMember): CastMember
    {

    }
    
    public function delete(string $castMemberId): bool
    {

    }

    private function convertToEntity(Model $model): CastMember
    {
        return new CastMember(
            id: new ValueObjectUuid($model->id),
            name: $model->name,
            type: CastMemberType::from($model->type),
            createdAt: $model->created_at
        );
    }
}
