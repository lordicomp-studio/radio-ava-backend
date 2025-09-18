<?php

namespace App\Http\Requests\Admin;

use App\Enums\ChatPriorityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TicketStoreAndUpdateRequest extends FormRequest
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
//        $toBeEditedTicket = $this->route('ticket');
        return [
            'sender_id' => 'required | integer',
            'receiver_id' => 'required | integer',
            'subject' => 'required | string | min:8 | max:255',
            'priority' => ['nullable', Rule::in(
                array_map( fn($item)=> $item->value , ChatPriorityEnum::cases())
            )],
            'status' => 'nullable | boolean',
        ];
    }
}
