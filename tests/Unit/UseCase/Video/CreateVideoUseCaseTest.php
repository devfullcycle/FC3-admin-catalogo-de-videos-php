<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\CreateVideoUseCase as UseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateVideoUseCaseTest extends TestCase
{
    public function test_example()
    {
        $useCase = new UseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
        );
    }

    private function createMockRepository()
    {
        return Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
    }

    private function createMockTransaction()
    {
        return Mockery::mock(stdClass::class, TransactionInterface::class);
    }
}
