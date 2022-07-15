<?php

namespace App\Http\Requests\Api\Home\Shop\Order;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            "customer_data" => ['required', 'json', /*Необходим вилидатор*/],
            "delivery_method_id" => ['required', 'exists:delivery_methods,id'],
            "order_items" => ['required', 'json', /*Необходим вилидатор*/],
        ];
    }
}
