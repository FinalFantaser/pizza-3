<?php

namespace App\Http\Requests\Api\Home\Shop\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => 'required|exists:users_address,id',
            'delivery_cost' => 'required|integer',
            'note' => 'nullable|string|max:500',
            'time' => 'required|string|max:255',
            'date' => 'required|string|max:255',
            //'delivery' => 'required|integer',
            //'note' => 'nullable|string|max:500',
            //'terms' => 'required|accepted'
        ];
    }
}
