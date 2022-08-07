<?php

namespace App\Http\Requests\Api\Admin\Shop\Payment\PaymentMethod;

use App\Models\Shop\Payment\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CreateRequest extends FormRequest
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
            'title' => 'required|string|unique:payment_methods,title',
            'code' => ['required', 'integer', Rule::in([PaymentMethod::CODE_CASH, PaymentMethod::CODE_CARD, PaymentMethod::CODE_ONLINE]) ],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
