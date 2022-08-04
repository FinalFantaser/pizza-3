<?php

namespace App\Http\Requests\Api\Admin\Shop\Payment\Yookassa;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:255|unique:yookassa_shops,name,'.$this->yookassa_shop->id,
            'shop_id' => 'required|string|max:255|unique:yookassa_shops,shop_id,'.$this->yookassa_shop->id,
            'api_token' => 'required|string',
            'cities' => 'array',
            'cities.*' => 'exists:cities,id',
        ];
    }
}
