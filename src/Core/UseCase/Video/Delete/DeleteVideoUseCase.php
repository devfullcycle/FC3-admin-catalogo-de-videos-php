<?php

namespace Core\UseCase\Video\Delete;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Delete\DTO\DeleteInputVideoDTO;
use Core\UseCase\Video\Delete\DTO\DeleteOutputVideoDTO;

class DeleteVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository,
    ) {
    }

    public function exec(DeleteInputVideoDTO $input): DeleteOutputVideoDTO
    {
        $deleted = $this->repository->delete($input->id);

        return new DeleteOutputVideoDTO(
            deleted: $deleted
        );
    }
}
