<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\Rating;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Create\DTO\{
    CreateInputVideoDTO,
    CreateOutputVideoDTO
};
use Core\UseCase\Video\Interfaces\{
    VideoEventManagerInterface
};
use Throwable;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,

        protected CategoryRepositoryInterface $repositoryCategory,
        protected GenreRepositoryInterface $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    ) {}

    public function exec(CreateInputVideoDTO $input): CreateOutputVideoDTO
    {
        $entity = $this->createEntity($input);

        try {
            $this->repository->insert($entity);

            if ($pathMedia = $this->storeMidea($entity->id(), $input->videoFile)) {
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            }

            $this->transaction->commit();

            return $this->ouput($entity);
        } catch (Throwable $th) {
            $this->transaction->rollback();

            throw $th;
        }
    }

    private function createEntity(CreateInputVideoDTO $input): Entity
    {
        // create entity -> $input
        $entity = new Entity(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: true,
            rating: $input->rating,
        );

        // add categories_ids in entity - validate
        $this->validateCategoriesId($input->categories);
        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        // add genres_ids in entity - validate
        $this->validateGenresId($input->genres);
        foreach ($input->genres as $genreId) {
            $entity->addGenre($genreId);
        }

        // add cast_members_ids in entity - validate
        $this->validateCastMembersId($input->castMembers);
        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMember($castMemberId);
        }

        return $entity;
    }

    private function storeMidea(string $path, ?array $media = null): string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media,
            );
        }

        return '';
    }

    private function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->repositoryCategory->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateGenresId(array $genresId = [])
    {
        $categoriesDb = $this->repositoryGenre->getIdsListIds($genresId);

        $arrayDiff = array_diff($genresId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateCastMembersId(array $castMembersIds = [])
    {
        $castMembersDB = $this->repositoryGenre->getIdsListIds($castMembersIds);

        $arrayDiff = array_diff($castMembersIds, $castMembersDB);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'CastMembers' : 'CastMember',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function ouput(Entity $entity): CreateOutputVideoDTO
    {
        return new CreateOutputVideoDTO(
            id: $entity->id(),
            title: $entity->title,
            description: $entity->description,
            yearLaunched: $entity->yearLaunched,
            duration: $entity->duration,
            opened: $entity->opened,
            rating: $entity->rating,
            categories: $entity->categoriesId,
            genres: $entity->genresId,
            castMembers: $entity->castMemberIds,
            videoFile: $entity->videoFile()?->filePath,
            trailerFile: $entity->trailerFile()?->filePath,
            thumbFile: $entity->thumbFile()?->filePath,
            thumbHalf: $entity->thumbHalf()?->filePath,
            bannerFile: $entity->thumbHalf()?->filePath,
        );
    }
}
