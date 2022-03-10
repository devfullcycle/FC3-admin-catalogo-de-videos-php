<?php

namespace Tests\Feature\Core\UseCase\Category;

use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\DeleteCategoryUseCase;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteCategoryUseCaseTest extends TestCase
{
    public function test_delete()
    {
        $categoryDb = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new DeleteCategoryUseCase($repository);
        $useCase->execute(
            new CategoryInputDto(
                id: $categoryDb->id
            )
        );

        $this->assertSoftDeleted($categoryDb);
    }
}
