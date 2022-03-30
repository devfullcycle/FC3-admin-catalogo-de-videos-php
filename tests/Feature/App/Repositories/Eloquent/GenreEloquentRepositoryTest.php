<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Tests\TestCase;

class GenreEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new GenreEloquentRepository(new Model());
    }
}
