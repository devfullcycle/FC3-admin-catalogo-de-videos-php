<?php

namespace Core\UseCase\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;

class ListCastMembersUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute()
    {
        
    }
}
