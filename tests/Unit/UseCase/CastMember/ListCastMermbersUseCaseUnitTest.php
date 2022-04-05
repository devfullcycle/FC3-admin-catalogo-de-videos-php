<?php

namespace Tests\Unit\UseCase\CastMember;

use Core\UseCase\CastMember\ListCastMembersUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;

class ListCastMermbersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list()
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
                            ->once()
                            ->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);
        $useCase->execute();

        $this->assertTrue(true);

        Mockery::close();
    }
}
