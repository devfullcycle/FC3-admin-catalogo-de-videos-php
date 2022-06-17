<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Video as Model;
use Core\Domain\Entity\Video as EntityVideo;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Enum\Rating;
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

    public function testInsert()
    {
        $entity = new EntityVideo(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
        );

        $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id(),
        ]);
    }
}
