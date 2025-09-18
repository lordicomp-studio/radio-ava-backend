<?php

namespace App\Http\Requests\Api\v1;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductEditRequestStoreRequest extends ProductStoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Product::where([
            'id' => $this->product_id,
            'creator_id' => auth()->id()
        ])->exists()){
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $sharedRules = parent::rules();

        $extraRules = [
            'product_id' => 'required|integer|exists:products,id',
            'note' => 'nullable',
        ];

        return array_merge($sharedRules, $extraRules);
    }
}
