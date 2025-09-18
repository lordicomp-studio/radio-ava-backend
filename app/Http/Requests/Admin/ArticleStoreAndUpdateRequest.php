<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleStoreAndUpdateRequest extends FormRequest
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
        $toBeEditedArticle = $this->route('article');

        return [
            'title' => 'required | min:8 | max:255',
            'slug' => ['nullable', 'min:8', 'max:255',
                isset($toBeEditedArticle)
                    ? Rule::unique('articles')->ignore($toBeEditedArticle->id)
                    : Rule::unique('articles')
            ],
            'photoId' => 'nullable | integer',
            'description' => 'nullable | string | max:500',
            'body' => 'nullable | string',
            'useRealDate' => 'nullable',
            'published_at' => $this->useRealDate ? 'nullable' : 'required',
            'categories' => 'nullable',
            'tags' => 'nullable',
            'status' => ['required', 'boolean'],
        ];
    }
}
