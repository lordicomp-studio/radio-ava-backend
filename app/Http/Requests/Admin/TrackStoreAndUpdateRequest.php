<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrackStoreAndUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $toBeEditedTrack = $this->route('track');

        return [
            'artist_id' => 'required|integer|exists:artists,id',
            'album_id' => 'nullable|integer|exists:albums,id',
            'cover_id' => 'nullable|integer|exists:media,id',
            'name' => 'required|string',
            'slug' => ['nullable',
                isset($toBeEditedTrack)
                    ? Rule::unique('tracks')->ignore($toBeEditedTrack->id)
                    : Rule::unique('tracks')
            ],
            'is_published' => 'nullable|boolean',
            'duration' => 'required|integer',
            'is_mv' => 'nullable|boolean',
            'lyrics_file_url' => 'nullable|string',
            'release_date' => 'nullable|date',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'track_files' => 'nullable|array',
            'track_files.*.quality' => 'required|string',
            'track_files.*.file_url' => 'required|string',
        ];
    }
}
