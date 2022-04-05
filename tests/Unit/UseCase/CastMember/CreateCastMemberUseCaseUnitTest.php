<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\UseCase\CastMember\CreateCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateCastMemberUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        // arrange
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $useCase = new CreateCastMemberUseCase($mockRepository);

        // action
        $useCase->execute();

        // assert
        $this->assertTrue(true);

        Mockery::close();
    }
}
