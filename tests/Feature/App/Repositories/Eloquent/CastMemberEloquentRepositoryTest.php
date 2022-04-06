<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use Core\Domain\Entity\CastMember as Entity;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CastMemberEloquentRepository(new Model());
    }

    public function testChechImplementsInterfaceRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new Entity(
            name: 'teste',
            type: CastMemberType::ACTOR,
        );

        $response = $this->repository->insert($entity);

        $this->assertDatabaseHas('cast_members', [
            'id' => $entity->id(),
        ]);
        $this->assertEquals($entity->name, $response->name);
    }

    public function testFindByIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_id');
    }

    public function testFindById()
    {
        $castMember = Model::factory()->create();

        $response = $this->repository->findById($castMember->id);

        $this->assertEquals($castMember->id, $response->id());
        $this->assertEquals($castMember->name, $response->name);
    }

    public function testFindAllEmtpy()
    {
        $response = $this->repository->findAll();
        $this->assertCount(0, $response);
    }

    public function testFindAll()
    {
        $castMembers = Model::factory()->count(50)->create();

        $response = $this->repository->findAll();

        $this->assertCount(count($castMembers), $response);
    }

    public function testPagination()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertCount(15, $response->items());
        $this->assertEquals(20, $response->total());
    }

    public function testPaginationWithTotalPage()
    
    {
        Model::factory()->count(80)->create();

        $response = $this->repository->paginate(
            totalPage: 10
        );

        $this->assertCount(10, $response->items());
        $this->assertEquals(80, $response->total());
    }
}
