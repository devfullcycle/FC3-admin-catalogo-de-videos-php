<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\CreateVideoUseCase as UseCase;
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseUnitTest extends TestCase
{
    protected $useCase;

    protected function setUp(): void
    {
        $this->useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),

            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );

        parent::setUp();
    }

    public function test_exec_input_output()
    {
        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(CreateOutputVideoDTO::class, $response);
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function test_exception_categories_ids(
        string $label,
        array $ids,
    ) {
        $this->expectException(NotFoundException::class);
        $this->expectErrorMessage(sprintf(
            '%s %s not found',
            $label,
            implode(', ', $ids)
        ));

        $this->useCase->exec(
            input: $this->createMockInputDTO(
                categoriesIds: $ids
            ),
        );
    }

    public function dataProviderIds(): array
    {
        return [
            ['Category', ['uuid-1']],
            ['Categories', ['uuid-1', 'uuid-2']],
            ['Categories', ['uuid-1', 'uuid-2', 'uuid-3', 'uuid-4']],
        ];
    }

    public function test_upload_files()
    {
        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(
                videoFile: ['tmp' => 'tmp/file.mp4'],
                trailerFile: ['tmp' => 'tmp/file.mp4'],
                thumbFile: ['tmp' => 'tmp/file.png'],
                thumbHalf: ['tmp' => 'tmp/file.png'],
                bannerFile: ['tmp' => 'tmp/file.png'],
            ),
        );
        
        $this->assertNotNull($response->videoFile);
        $this->assertNotNull($response->trailerFile);
        $this->assertNotNull($response->thumbFile);
    }

    private function createMockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('insert')
                                ->andReturn($this->createMockEntity());
        $mockRepository->shouldReceive('updateMedia');

        return $mockRepository;
    }

    private function createMockRepositoryCategory(array $categoriesRenponse = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')->andReturn($categoriesRenponse);

        return $mockRepository;
    }

    private function createMockRepositoryGenre(array $genresResponseIds = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')->andReturn($genresResponseIds);

        return $mockRepository;
    }

    private function createMockRepositoryCastMember(array $castMemberResponseIds = [])
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mockRepository->shouldReceive('getIdsListIds')->andReturn($castMemberResponseIds);

        return $mockRepository;
    }

    private function createMockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function createMockFileStorage()
    {
        $mockFileStorage = Mockery::mock(stdClass::class, FileStorageInterface::class);

        $mockFileStorage->shouldReceive('store')
                        ->andReturn('path/file.png');

        return $mockFileStorage;
    }

    private function createMockEventManager()
    {
        $mockEventManager = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mockEventManager->shouldReceive('dispatch');

        return $mockEventManager;           
    }

    private function createMockInputDTO(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    ) {
        return Mockery::mock(CreateInputVideoDTO::class, [
            'title',
            'desc',
            2023,
            12,
            true,
            Rating::RATE10,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }

    private function createMockEntity()
    {
        return Mockery::mock(Entity::class, [
            'title',
            'description',
            2026,
            1,
            true,
            Rating::RATE10,
        ]);
    }
}
