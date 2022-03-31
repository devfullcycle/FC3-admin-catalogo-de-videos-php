<?php

namespace Tests\Feature\Core\UseCase\Genre;

use App\Models\Genre;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\UseCase\DTO\Genre\List\ListGenresInputDto;
use Core\UseCase\Genre\ListGenresUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    public function testFindAll()
    {
        $useCase = new ListGenresUseCase(
            new GenreEloquentRepository(new Genre())
        );

        Genre::factory()->count(100)->create();

        $responseUseCase = $useCase->execute(
            new ListGenresInputDto()
        );

        $this->assertEquals(15, count($responseUseCase->items));
        $this->assertEquals(100, $responseUseCase->total);
    }
}
