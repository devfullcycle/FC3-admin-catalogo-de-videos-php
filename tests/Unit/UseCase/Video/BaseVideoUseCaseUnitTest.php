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
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class BaseVideoUseCaseUnitTest extends TestCase
{
    protected $useCase;

    abstract protected function nameActionRepository(): string;
    abstract protected function getUseCase(): string;
    abstract protected function createMockInputDTO(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null,
    );

    protected function createUseCase(
        int $timesCallMethodActionRepository = 1,
        int $timesCallMethodUpdateMediaRepository = 1,

        int $timesCallMethodCommitTransaction = 1,
        int $timesCallMethodRollbackTransaction = 0,

        int $timesCallMethodStoreFileStorage = 0,

        int $timesCallMethodDispatchEventManager = 0
    ) {
        $this->useCase = new ($this->getUseCase())(
            repository: $this->createMockRepository(
                timesCallAction: $timesCallMethodActionRepository,
                timesCallUpdateMedia: $timesCallMethodUpdateMediaRepository,
            ),
            transaction: $this->createMockTransaction(
                timesCallCommit: $timesCallMethodCommitTransaction,
                timesCallRollback: $timesCallMethodRollbackTransaction,
            ),
            storage: $this->createMockFileStorage(
                timesCall: $timesCallMethodStoreFileStorage,
            ),
            eventManager: $this->createMockEventManager(
                times: $timesCallMethodDispatchEventManager
            ),

            repositoryCategory: $this->createMockRepositoryCategory(),
            repositoryGenre: $this->createMockRepositoryGenre(),
            repositoryCastMember: $this->createMockRepositoryCastMember(),
        );
    }

    /**
     * @dataProvider dataProviderIds
     */
    public function test_exception_categories_ids(
        string $label,
        array $ids,
    ) {
        $this->createUseCase(
            timesCallMethodActionRepository: 0,
            timesCallMethodUpdateMediaRepository: 0,
            timesCallMethodCommitTransaction: 0
        );

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

    /**
     * @dataProvider dataProviderFiles
     */
    public function test_upload_files(
        array $video,
        array $trailer,
        array $thumb,
        array $thumbHalf,
        array $banner,
        int $storage,
        int $event = 0,
    ) {
        $this->createUseCase(
            timesCallMethodStoreFileStorage: $storage,
            timesCallMethodDispatchEventManager: $event
        );

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(
                videoFile: $video['value'],
                trailerFile: $trailer['value'],
                thumbFile: $thumb['value'],
                thumbHalf: $thumbHalf['value'],
                bannerFile: $banner['value'],
            ),
        );
        
        $this->assertEquals($response->videoFile, $video['expected']);
        $this->assertEquals($response->trailerFile, $trailer['expected']);
        $this->assertEquals($response->thumbFile, $thumb['expected']);
        $this->assertEquals($response->thumbHalf, $thumbHalf['expected']);
        $this->assertEquals($response->bannerFile, $banner['expected']);
    }

    public function dataProviderFiles(): array
    {
        return [
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'timesStorage' => 5,
                'dispatch' => 1,
            ], [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'timesStorage' => 3,
                'dispatch' => 1,
            ], [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.png'],
                'timesStorage' => 2,
            ], [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'timesStorage' => 0,
            ],
        ];
    }

    private function createMockRepository(
        int $timesCallAction,
        int $timesCallUpdateMedia,
    ) {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive($this->nameActionRepository())
                                ->times($timesCallAction)
                                ->andReturn($this->createMockEntity());
        $mockRepository->shouldReceive('updateMedia')
                        ->times($timesCallUpdateMedia);

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

    private function createMockTransaction(
        int $timesCallCommit,
        int $timesCallRollback
    ) {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mockTransaction->shouldReceive('commit')->times($timesCallCommit);
        $mockTransaction->shouldReceive('rollback')->times($timesCallRollback);

        return $mockTransaction;
    }

    private function createMockFileStorage(int $timesCall)
    {
        $mockFileStorage = Mockery::mock(stdClass::class, FileStorageInterface::class);

        $mockFileStorage->shouldReceive('store')
                        ->times($timesCall)
                        ->andReturn('path/file.png');

        return $mockFileStorage;
    }

    private function createMockEventManager(int $times)
    {
        $mockEventManager = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);
        $mockEventManager->shouldReceive('dispatch')->times($times);

        return $mockEventManager;           
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

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
