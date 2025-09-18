<?php

namespace App\Http\Requests\Api\v1;

use App\Services\Api\Payments\PaymentGatewayServiceInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class PaymentCallbackRequest extends FormRequest
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
        $rules = [
            'gateway' => ['required', Rule::in(PaymentGatewayServiceInterface::GATEWAYS)],
            'token' => 'required',
        ];

        if ($this->input('gateway') === 'Crypto'){
            $rules = array_merge($rules, [
                'hash' => 'required',
                'status' => ['required', Rule::in(['Completed', 'Failed'])],
            ]);
        } /*elseif ($this->input('gateway') === 'Paypal'){
            // do nothing
        }*/

        return $rules;
    }
}
