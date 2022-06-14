<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageVideo extends Model
{
    use HasFactory;

    protected $table = 'images_video';

    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}
