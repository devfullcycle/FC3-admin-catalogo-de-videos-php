<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember as Model;
use Core\Domain\Entity\CastMember as Entity;
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CastMemberEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CastMemberEloquentRepository(new Model());
    }

    public function testChechImplementsInterfaceRepository()
    {
        $this->assertInstanceOf(CastMemberRepositoryInterface::class, $this->repository);
    }
}
