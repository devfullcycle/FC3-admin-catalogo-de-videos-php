<?php

namespace Tests\Unit\UseCase\Genre;

use Core\UseCase\DTO\Genre\List\{
    ListGenresInputDto
};
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\Genre\ListGenresUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
    public function test_usecase()
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockDtoInput = Mockery::mock(ListGenresInputDto::class, [
            'teste', 'desc', 1, 15
        ]);

        $useCase = new ListGenresUseCase($mockRepository);
        $useCase->execute($mockDtoInput);

        Mockery::close();
    }
}
