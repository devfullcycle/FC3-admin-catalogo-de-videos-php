<?php

namespace App\Models;

use App\Enums\ImageTypes;
use App\Enums\MediaTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }

    public function castMembers()
    {
        return $this->belongsToMany(CastMember::class, 'cast_member_video');
    }

    public function media()
    {
        return $this->hasOne(Media::class)
                        ->where('type', MediaTypes::VIDEO->value);
    }

    public function trailer()
    {
        return $this->hasOne(Media::class)
                        ->where('type', MediaTypes::TRAILER->value);
    }

    public function banner()
    {
        return $this->hasOne(ImageVideo::class)
                        ->where('type', ImageTypes::BANNER->value);
    }

    public function thumb()
    {
        return $this->hasOne(ImageVideo::class)
                        ->where('type', ImageTypes::THUMB->value);
    }

    public function thumbHalf()
    {
        return $this->hasOne(ImageVideo::class)
                        ->where('type', ImageTypes::THUMB_HALF->value);
    }
}
