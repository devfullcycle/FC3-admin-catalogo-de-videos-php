<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\{
    CastMember,
    Category,
    Genre
};
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\Stubs\UploadFilesStub;
use Tests\Stubs\VideoEventStub;
use Tests\TestCase;
use Throwable;

abstract class BaseVideoUseCase extends TestCase
{
    abstract function useCase(): string;
    abstract function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $bannerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
    ): object;

    /**
     * @dataProvider provider
     */
    public function test_action(
        int $categories,
        int $genres,
        int $castMembers,
        bool $withMediaVideo = false,
        bool $withTrailer = false,
        bool $withThumb = false,
        bool $withThumbHalf = false,
        bool $withBanner = false,
    ) {
        $stu = $this->makeSut();

        $categoriesIds = Category::factory()->count($categories)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count($genres)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count($castMembers)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = $this->inputDTO(
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $withMediaVideo ? $file : null,
            trailerFile: $withTrailer ? $file : null,
            bannerFile: $withBanner ? $file : null,
            thumbFile: $withThumb ? $file : null,
            thumbHalf: $withThumbHalf ? $file : null,
        );

        $response = $stu->exec($input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        // $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        // $this->assertEquals($input->duration, $response->duration);
        // $this->assertEquals($input->opened, $response->opened);
        // $this->assertEquals($input->rating, $response->rating);

        $this->assertCount($categories, $response->categories);
        $this->assertEqualsCanonicalizing($input->categories, $response->categories);
        $this->assertCount($genres, $response->genres);
        $this->assertEqualsCanonicalizing($input->genres, $response->genres);
        $this->assertCount($castMembers, $response->castMembers);
        $this->assertEqualsCanonicalizing($input->castMembers, $response->castMembers);

        $this->assertTrue($withMediaVideo ? $response->videoFile !== null : $response->videoFile === null);
        $this->assertTrue($withTrailer ? $response->trailerFile !== null : $response->trailerFile === null);
        $this->assertTrue($withBanner ? $response->bannerFile !== null : $response->bannerFile === null);
        $this->assertTrue($withThumb ? $response->thumbFile !== null : $response->thumbFile === null);
        $this->assertTrue($withThumbHalf ? $response->thumbHalf !== null : $response->thumbHalf === null);
    }

    protected function provider(): array
    {
        return [
            'Test with all IDs and media video' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 3,
                'withMediaVideo' => true,
                'withTrailer' => false,
                'withThumb' => false,
                'withThumbHalf' => false,
                'withBanner' => false,
            ],
            'Test with categories and genres and without files' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 0,
            ],
            'Test with all IDs and all medias' => [
                'categories' => 2,
                'genres' => 2,
                'castMembers' => 2,
                'withMediaVideo' => true,
                'withTrailer' => true,
                'withThumb' => true,
                'withThumbHalf' => true,
                'withBanner' => true,
            ],
            'Test without IDs and all medias' => [
                'categories' => 0,
                'genres' => 0,
                'castMembers' => 0,
                'withMediaVideo' => true,
                'withTrailer' => true,
                'withThumb' => true,
                'withThumbHalf' => true,
                'withBanner' => true,
            ],
        ];
    }

    protected function makeSut()
    {
        return new ($this->useCase())(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            // this->app->make(FileStorageInterface::class),
            new UploadFilesStub(),
            // $this->app->make(VideoEventManagerInterface::class),
            new VideoEventStub(),

            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class)
        );
    }

    /**
     * @test
     */
    public function transanctionException()
    {
        // $this->expectException(Exception::class);

        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('begin transaction');
        });

        try {
            $sut = $this->makeSut();
            $sut->exec($this->inputDTO());

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            //throw $th;
        }
    }

    /**
     * @test
     */
    public function uploadFilesException()
    {
        Event::listen(UploadFilesStub::class, function () {
            throw new Exception('upload files');
        });

        try {
            $sut = $this->makeSut();
            $input = $this->inputDTO(
                trailerFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );
            $sut->exec($input);

            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            //throw $th;
        }
    }

    /**
     * @test
     */
    public function eventException()
    {
        Event::listen(VideoEventStub::class, function () {
            throw new Exception('event');
        });

        try {
            $sut = $this->makeSut();
            $sut->exec($this->inputDTO(
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            ));

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            //throw $th;
        }
    }
}
