<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Create\CreateVideoUseCase as UseCase;
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseTest extends TestCase
{
    public function test_constructor()
    {
        new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),
        );

        $this->assertTrue(true);
    }

    public function test_exec_input_output()
    {
        $useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockFileStorage(),
            eventManager: $this->createMockEventManager(),
        );

        $response = $useCase->exec(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(CreateOutputVideoDTO::class, $response);
    }

    private function createMockRepository()
    {
        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mockRepository->shouldReceive('insert')
                                ->andReturn($this->createMockEntity());

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

    private function createMockInputDTO()
    {
        return Mockery::mock(CreateInputVideoDTO::class, [
            'title',
            'desc',
            2023,
            12,
            true,
            Rating::RATE10,
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
