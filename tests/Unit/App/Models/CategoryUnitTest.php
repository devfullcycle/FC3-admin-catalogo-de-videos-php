<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CategoryUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new Category();
    }

    protected function traits(): array
    {
        return [
            HasFactory::class,
            SoftDeletes::class,
        ];
    }
    
    protected function fillables(): array
    {
        return [
            'id',
            'name',
            'description',
            'is_active',
        ];
    }

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime'
        ];
    }
}
