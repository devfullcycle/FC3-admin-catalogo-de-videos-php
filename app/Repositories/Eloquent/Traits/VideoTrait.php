<?php

namespace App\Repositories\Eloquent\Traits;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use Core\Domain\Entity\Video as Entity;;
use Illuminate\Database\Eloquent\Model;

trait VideoTrait
{
    public function updateMediaVideo(Entity $entity, Model $model): void
    {
        if ($mediaVideo = $entity->videoFile()) {
            $action = $model->media()->first() ? 'update' : 'create';
            $model->media()->{$action}([
                'file_path' => $mediaVideo->filePath,
                'media_status' => $mediaVideo->mediaStatus->value,
                'encoded_path' => $mediaVideo->encodedPath,
                'type' => MediaTypes::VIDEO->value,
            ]);
        }
    }

    public function updateMediaTrailer(Entity $entity, Model $model): void
    {
        if ($trailer = $entity->trailerFile()) {
            $action = $model->trailer()->first() ? 'update' : 'create';
            $model->trailer()->{$action}([
                'file_path' => $trailer->filePath,
                'media_status' => $trailer->mediaStatus->value,
                'encoded_path' => $trailer->encodedPath,
                'type' => MediaTypes::TRAILER->value,
            ]);
        }
    }

    public function updateImageBanner(Entity $entity, Model $model): void
    {
        if ($banner = $entity->bannerFile()) {
            $action = $model->banner()->first() ? 'update' : 'create';
            $model->banner()->{$action}([
                'path' => $banner->path(),
                'type' => ImageTypes::BANNER->value,
            ]);
        }
    }

    public function updateImageThumb(Entity $entity, Model $model): void
    {
        if ($thumb = $entity->thumbFile()) {
            $action = $model->thumb()->first() ? 'update' : 'create';
            $model->thumb()->{$action}([
                'path' => $thumb->path(),
                'type' => ImageTypes::THUMB->value,
            ]);
        }
    }
}
