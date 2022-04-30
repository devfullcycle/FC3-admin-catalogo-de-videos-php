<?php

namespace Core\Domain\Entity;

use Core\Domain\Entity\Traits\MethodsMagicsTrait;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Notification\Notification;
use Core\Domain\Validation\DomainValidation;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use DateTime;

class Video extends Entity
{
    protected array $categoriesId = [];
    protected array $genresId = [];
    protected array $castMemberIds = [];

    public function __construct(
        protected string $title,
        protected string $description,
        protected int $yearLaunched,
        protected int $duration,
        protected bool $opened,
        protected Rating $rating,
        protected ?Uuid $id = null,
        protected bool $published = false,
        protected ?DateTime $createdAt = null,
        protected ?Image $thumbFile = null,
        protected ?Image $thumbHalf = null,
        protected ?Image $bannerFile = null,
        protected ?Media $trailerFile = null,
        protected ?Media $videoFile = null,
    ) {
        parent::__construct();

        $this->id = $this->id ?? Uuid::random();
        $this->createdAt = $this->createdAt ?? new DateTime();

        $this->validation();
    }

    public function addCategoryId(string $categoryId)
    {
        array_push($this->categoriesId, $categoryId);
    }

    public function removeCategoryId(string $categoryId)
    {
        unset($this->categoriesId[array_search($categoryId, $this->categoriesId)]);
    }

    public function addGenre(string $genreId)
    {
        array_push($this->genresId, $genreId);
    }

    public function removeGenre(string $genreId)
    {
        unset($this->genresId[array_search($genreId, $this->genresId)]);
    }

    public function addCastMember(string $castMemberId)
    {
        array_push($this->castMemberIds, $castMemberId);
    }

    public function removeCastMember(string $castMemberId)
    {
        unset($this->castMemberIds[array_search($castMemberId, $this->castMemberIds)]);
    }

    public function thumbFile(): ?Image
    {
        return $this->thumbFile;
    }

    public function thumbHalf(): ?Image
    {
        return $this->thumbHalf;
    }

    public function bannerFile(): ?Image
    {
        return $this->bannerFile;
    }

    public function trailerFile(): ?Media
    {
        return $this->trailerFile;
    }

    public function videoFile(): ?Media
    {
        return $this->videoFile;
    }

    protected function validation()
    {
        if (empty($this->title)) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'Should not be empty or null',
            ]);
        }

        if (strlen($this->title) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'invalid qtd',
            ]);
        }

        if (strlen($this->description) < 3) {
            $this->notification->addError([
                'context' => 'video',
                'message' => 'invalid qtd',
            ]);
        }

        if ($this->notification->hasErrors())
            throw new EntityValidationException(
                $this->notification->messages('video')
            );
    }
}
