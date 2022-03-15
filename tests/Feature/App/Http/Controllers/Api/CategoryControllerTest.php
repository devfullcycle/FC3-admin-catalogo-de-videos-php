<?php

namespace Tests\Feature\App\Http\Controllers\Api;

use App\Http\Controllers\Api\CategoryController;
use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\UseCase\Category\{
    ListCategoriesUseCase
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        $this->repository = new CategoryEloquentRepository(
            new Category()
        );

        parent::setUp();
    }

    public function test_index()
    {
        $useCase = new ListCategoriesUseCase($this->repository);

        $controller = new CategoryController();
        $response = $controller->index(new Request(), $useCase);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
        $this->assertArrayHasKey('meta', $response->additional);
    }
}
