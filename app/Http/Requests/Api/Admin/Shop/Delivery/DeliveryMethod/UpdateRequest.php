<?php

namespace App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryMethod;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Shop\Delivery\DeliveryMethod;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string||in:'.DeliveryMethod::TYPE_PICKUP.','.DeliveryMethod::TYPE_COURIER,
            'cost' => 'required|integer|min:0',
            'free_from' => 'integer|min:0',
            'min_weight' => 'required|integer|min:0',
            'max_weight' => 'required|integer',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
