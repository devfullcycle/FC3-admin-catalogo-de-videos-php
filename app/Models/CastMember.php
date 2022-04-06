<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastMember extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'type',
    ];

    public $incrementing = false;

    protected $casts = [
        'id' => 'string',
        'deleted_at' => 'datetime',
    ];
}
