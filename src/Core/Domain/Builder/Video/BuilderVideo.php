<?php

namespace Core\Domain\Builder\Video;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;

class BuilderVideo implements Builder
{
    protected ?Entity $entity = null;

    public function __construct()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->entity = null;
    }

    public function createEntity(object $input): Builder
    {
        $this->entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
        );

        $this->addIds($input);

        return $this;
    }

    protected function addIds(object $input)
    {
        foreach ($input->categories as $categoryId) {
            $this->entity->addCategoryId($categoryId);
        }

        foreach ($input->genres as $genreId) {
            $this->entity->addGenre($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $this->entity->addCastMember($castMemberId);
        }
    }
    
    public function addMediaVideo(string $path, MediaStatus $mediaStatus, string $encodedPath = ''): Builder
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: $mediaStatus,
            encodedPath: $encodedPath
        );
        $this->entity->setVideoFile($media);

        return $this;
    }
    
    public function addTrailer(string $path): Builder
    {
        $media = new Media(
            filePath: $path,
            mediaStatus: MediaStatus::COMPLETE
        );
        $this->entity->setTrailerFile($media);

        return $this;
    }
    
    public function addThumb(string $path): Builder
    {
        $this->entity->setThumbFile(new Image(
            path: $path
        ));

        return $this;
    }
    
    public function addThumbHalf(string $path): Builder
    {
        $this->entity->setThumbHalf(new Image(
            path: $path
        ));

        return $this;
    }
    
    public function addBanner(string $path): Builder
    {
        $this->entity->setBannerFile(new Image(
            path: $path
        ));

        return $this;
    }
    
    public function getEntity(): Entity
    {
        return $this->entity;
    }
}
