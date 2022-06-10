<?php

namespace Core\UseCase\Video\Paginate;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Video\Paginate\DTO\{
    PaginateInputVideoDTO,
    PaginateOutputVideoDTO,
};

class ListVideosUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) {}

    public function exec(PaginateInputVideoDTO $input): PaginateOutputVideoDTO
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPerPage
        );

        return new PaginateOutputVideoDTO(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
