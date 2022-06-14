<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Video as Model;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Repository\VideoRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new VideoEloquentRepository(
            new Model()
        );
    }
    
    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            VideoRepositoryInterface::class,
            $this->repository
        );
    }
}
