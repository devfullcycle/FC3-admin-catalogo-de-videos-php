<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Interfaces\{
    VideoEventManagerInterface
};

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
    ) {}
}
