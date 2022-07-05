<?php

namespace App\Http\Requests\Api\Home\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|integer|min:1|exists:products,id',
            'quantity' => 'required|integer|min:1',
            //TODO сделать проверку, чтобы продукт был из того же города, из которого заказывает пользователь
        ];
    }
}
