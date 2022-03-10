<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\CreateCategoryUseCase;
use Core\UseCase\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateCategoryUseCaseTest extends TestCase
{
    public function test_create()
    {
        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new CreateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategoryCreateInputDto(
                name: 'Teste',
            )
        );

        $this->assertEquals('Teste', $responseUseCase->name);
        $this->assertNotEmpty($responseUseCase->id);

        $this->assertDatabaseHas('categories', [
            'id' => $responseUseCase->id
        ]);
    }
}
