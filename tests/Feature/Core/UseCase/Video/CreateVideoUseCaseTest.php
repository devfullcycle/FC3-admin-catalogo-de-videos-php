<?php

namespace Tests\Feature\Core\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\Event;
use Tests\Stubs\UploadFilesStub;
use Throwable;

class CreateVideoUseCaseTest extends BaseVideoUseCase
{
    public function useCase(): string
    {
        return CreateVideoUseCase::class;
    }
    
    public function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $bannerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
    ): object
    {
        return new CreateInputVideoDTO(
            title: 'test',
            description: 'test',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::L,
            categories: $categories,
            genres: $genres,
            castMembers: $castMembers,
            videoFile: $videoFile,
            trailerFile: $trailerFile,
            bannerFile: $bannerFile,
            thumbFile: $thumbFile,
            thumbHalf: $thumbHalf,
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
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );
            $sut->exec($input);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            //throw $th;
        }
    }
}
