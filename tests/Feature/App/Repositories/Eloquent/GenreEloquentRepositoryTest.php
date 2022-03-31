<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Category;
use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\GenreRepositoryInterface;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new Model());
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(GenreRepositoryInterface::class, $this->repository);
    }

    public function testInsert()
    {
        $entity = new EntityGenre(name: 'New genre');

        $response = $this->repository->insert($entity);
        $this->assertEquals($entity->name, $response->name);
        $this->assertEquals($entity->id, $response->id);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id()
        ]);
    }

    public function testInsertDeactivate()
    {
        $entity = new EntityGenre(name: 'New genre');
        $entity->deactivate();

        $this->repository->insert($entity);

        $this->assertDatabaseHas('genres', [
            'id' => $entity->id(),
            'is_active' => false
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();

        $genre = new EntityGenre(name: 'teste');
        foreach ($categories as $category) {
            $genre->addCategory($category->id);
        }

        $response = $this->repository->insert($genre);

        $this->assertDatabaseHas('genres', [
            'id' => $response->id(),
        ]);

        $this->assertDatabaseCount('category_genre', 4);
    }

    public function testNotFoundById()
    {
        $this->expectException(NotFoundException::class);

        $genre = 'fake_value';

        $this->repository->findById($genre);
    }

    public function testFindById()
    {
        $genre = Model::factory()->create();

        $response = $this->repository->findById($genre->id);

        $this->assertEquals($genre->id, $response->id());
        $this->assertEquals($genre->name, $response->name);
    }

    public function testFindAll()
    {
        $genres = Model::factory()->count(10)->create();

        $genresDb = $this->repository->findAll();

        $this->assertEquals(count($genres), count($genresDb));
    }

    public function testFindAllEmpty()
    {
        $genresDb = $this->repository->findAll();

        $this->assertCount(0, $genresDb);
    }

    public function testFindAllWithFilter()
    {
        Model::factory()->count(10)->create([
            'name' => 'Teste'
        ]);
        Model::factory()->count(10)->create();

        $genresDb = $this->repository->findAll(
            filter: 'Teste'
        );
        $this->assertEquals(10, count($genresDb));

        $genresDb = $this->repository->findAll();
        $this->assertEquals(20, count($genresDb));
    }
}
