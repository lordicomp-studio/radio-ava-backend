<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserLevelEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreAndUpdateRequest extends FormRequest
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
        $toBeEditedUser = $this->route('user');

        return [
            'name' => 'required | min:2 | max:255',
            'first_name' => 'nullable | max:255',
            'last_name' => 'nullable | max:255',
            'email' => ['required', 'email', 'max:255',
                isset($toBeEditedUser)
                    ? Rule::unique('users')->ignore($toBeEditedUser->id)
                    : Rule::unique('users')],
            'password' => [isset($toBeEditedUser) ? 'nullable' : 'required', 'min:8'],
            'profilePhotoId' => 'nullable | integer',
            'roleName' => 'required',
            'level' => ['required', Rule::in(
                array_map( fn($item)=> $item->value , UserLevelEnum::cases())
            )],
        ];
    }
}
