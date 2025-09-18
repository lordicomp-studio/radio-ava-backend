<?php

namespace App\Http\Requests\Admin;

use App\Enums\ChatPriorityEnum;
use App\Enums\ProductStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductStoreUpdateConfirmRequest extends FormRequest
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
        $toBeEditedProduct = $this->route('product'); // this is also used for the confirmFinalize request
        $toBeConfirmedEditRequest = $this->route('productEditRequest');

        return [
            'name' => 'required | min:8 | max:255',
            'slug' => ['nullable', 'min:8', 'max:255',
                $this->handleSlugUniqueRule($toBeEditedProduct, $toBeConfirmedEditRequest)],
            'file_url' => 'nullable',
            'photoId' => 'nullable | integer',
            'description' => 'nullable | string | max:500',
            'body' => 'nullable | string',
            'useRealDate' => 'nullable',
            'published_at' => $this->useRealDate ? 'nullable' : 'required',
            'categories' => 'nullable',
            'tags' => 'nullable',
            'status' => ['required', Rule::in(
                array_map( fn($item)=> $item->value , ProductStatusEnum::cases())
            )],
            'price' => 'nullable | int',
            'address_url' => 'nullable',
            'version' => 'nullable',
            'features' => 'nullable',
            'features.*.title' => 'required',
        ];
    }

    private function handleSlugUniqueRule($toBeEditedProduct, $toBeConfirmedEditRequest)
    {
        if (isset($toBeConfirmedEditRequest)){
            return Rule::unique('products')->ignore($toBeConfirmedEditRequest->product_id);
        }

        return isset($toBeEditedProduct)
            ? Rule::unique('products')->ignore($toBeEditedProduct->id)
            : Rule::unique('products');
    }
}
