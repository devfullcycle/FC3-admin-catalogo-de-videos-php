<?php

namespace Core\UseCase\Video\List;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\List\DTO\{
    ListInputVideoUseCase,
    ListOutputVideoUseCase
};

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository,
    ) {}

    public function exec(ListInputVideoUseCase $input): ListOutputVideoUseCase
    {
        $entity = $this->repository->findById($input->id);

        return new ListOutputVideoUseCase(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            createdAt: $entity->createdAt(),
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMemberIds,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->path(),
            thumbHalf: $entity->thumbHalf()?->path(),
            bannerFile: $entity->bannerFile()?->path(),
        );
    }
}
