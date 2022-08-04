<?php

namespace App\Http\Requests\Api\Admin\Shop\Payment\Yookassa;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'nullable|string|max:255|unique:yookassa_shops,name',
            'shop_id' => 'required|string|max:255|unique:yookassa_shops,shop_id',
            'api_token' => 'required|string',
            'cities' => 'array',
            'cities.*' => 'exists:cities,id',
        ];
    }
}
