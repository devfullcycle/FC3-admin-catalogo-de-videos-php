<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait UuidTrait
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
