<?php

namespace Tests\Unit\UseCase\Genre;

use Core\UseCase\DTO\Genre\{
    GenreInputDto,
    GenreOutputDto,
};
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\UseCase\Genre\ListGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ListGenreUseCaseUnitTest extends TestCase
{
    public function test_list_single()
    {
        $uuid = (string) Uuid::uuid4();

        $mockEntity = Mockery::mock(EntityGenre::class, [
            'teste', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(GenreInputDto::class, [
            $uuid
        ]);
        
        $useCase = new ListGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(GenreOutputDto::class, $response);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($mockEntity);
        $useCase = new ListGenreUseCase($this->spy);
        $response = $useCase->execute($mockInputDto);
        $this->spy->shouldHaveReceived('findById');

        Mockery::close();
    }
}
