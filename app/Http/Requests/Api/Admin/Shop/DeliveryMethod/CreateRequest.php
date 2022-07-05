<?php

namespace App\Http\Requests\Api\Admin\Shop\DeliveryMethod;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'cost' => 'required|integer|min:0',
            'free_from' => 'nullable|integer|min:0',
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'required|integer',
            'sort' => 'required|integer'
        ];
    }
}
