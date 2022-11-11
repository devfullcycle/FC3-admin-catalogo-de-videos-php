<?php

namespace Tests\Stubs;

use Core\UseCase\Interfaces\FileStorageInterface;

class UploadFilesStub implements FileStorageInterface
{
    public function __construct()
    {
        event($this);
    }

    /**
     * @param  string  $path
     * @param  array  $_FILES[file]
     */
    public function store(string $path, array $file): string
    {
        return "{$path}/test.mp4";
    }

    public function delete(string $path)
    {
        //
    }
}
