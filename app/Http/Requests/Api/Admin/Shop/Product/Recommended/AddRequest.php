<?php

namespace App\Http\Requests\Api\Admin\Shop\Product\Recommended;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'data' => 'required|array',
            'data.*.product_id' => 'required|exists:products,id',
            'data.*.sort' => 'required|integer|gte:0',
        ];
    }
}
