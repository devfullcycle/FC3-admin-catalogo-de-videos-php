<?php

namespace App\Http\Requests;

use Core\Domain\Enum\Rating;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => [
                'required',
                'max: 255',
            ],
            'description' => [
                'required'
            ],
            'year_launched' => 'required|date_format:Y',
            'opened' => 'required|boolean',
            'rating' => [
                'required',
                new Enum(Rating::class)
            ],
            'duration' => 'required|integer',
            'categories' => 'required|array|exists:categories,id,deleted_at,NULL',
            'genres' => [
                'required',
                'array',
                'exists:genres,id,deleted_at,NULL',
            ],
            'cast_members' => 'required|array|exists:cast_members,id,deleted_at,NULL',
            'thumb_file' => 'nullable|image', //5MB
            'thumb_half_file' => 'nullable|image', //5MB
            'banner_file' => 'nullable|image', //10MB
            'trailer_file' => 'nullable|mimetypes:video/mp4', //1GB
            'video_file' => 'nullable|mimetypes:video/mp4', //50GB
        ];
    }
}
