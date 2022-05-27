<?php

namespace Core\UseCase\Video\Create;

use Core\Domain\Entity\Video as Entity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
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
    protected Entity $entity;

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
        $this->entity = $this->createEntity($input);

        try {
            $this->repository->insert($this->entity);

            $this->storageFiles($input);

            $this->repository->updateMedia($this->entity);

            $this->transaction->commit();

            return $this->ouput($this->entity);
        } catch (Throwable $th) {
            $this->transaction->rollback();
            // if (isset($pathMedia)) $this->storage->delete($pathMedia);
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

    protected function storageFiles(object $input): void
    {
        if ($pathVideoFile = $this->storageFile($this->entity->id(), $input->videoFile)) {
            $media = new Media(
                filePath: $pathVideoFile,
                mediaStatus: MediaStatus::PROCESSING
            );
            $this->entity->setVideoFile($media);
            $this->eventManager->dispatch(new VideoCreatedEvent($this->entity));
        }

        if ($pathBannerFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setTrailerFile(new Media(
                filePath: $pathBannerFile,
                mediaStatus: MediaStatus::PROCESSING
            ));
        }

        if ($pathThumbFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setThumbFile(new Image(
                path: $pathThumbFile
            ));
        }

        if ($pathThumbHalfFile = $this->storageFile($this->entity->id(), $input->thumbHalf)) {
            $this->entity->setThumbHalf(new Image(
                path: $pathThumbHalfFile
            ));
        }

        if ($pathBannerFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $this->entity->setBannerFile(new Image(
                path: $pathBannerFile
            ));
        }
    }

    private function storageFile(string $path, ?array $media = null): null|string
    {
        if ($media) {
            return $this->storage->store(
                path: $path,
                file: $media,
            );
        }

        return null;
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
