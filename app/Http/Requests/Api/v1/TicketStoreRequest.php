<?php

namespace App\Http\Requests\Api\v1;

use App\Enums\ChatPriorityEnum;
use App\Enums\ChatTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketStoreRequest extends FormRequest
{
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
        return [
            'receiver_id' => 'required_unless:chatType,Support',
            'subject' => 'required | string | min:8 | max:255',
            'priority' => ['required', Rule::in(
                array_map( fn($item)=> $item->value , ChatPriorityEnum::cases())
            )],
            'chatType' => ['required', Rule::in(
                array_map( fn($item)=> $item->value , ChatTypeEnum::cases())
            )],
            'message' => 'required_without:file | min:2',
            'file' => 'nullable | max:5000',
        ];
    }
}
