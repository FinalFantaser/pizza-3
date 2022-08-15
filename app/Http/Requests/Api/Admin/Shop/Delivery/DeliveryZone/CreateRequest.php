<?php

namespace App\Http\Requests\Api\Admin\Shop\Delivery\DeliveryZone;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
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
            'city_id' => 'required|exists:cities,id',
            'restaurant_id' => 'required|string|size:6',
            'code' => 'required|string|size:6|unique:delivery_zones,code',
            'name' => 'required|string|max:512|unique:delivery_zones,name',
            'sum_min' => 'required|numeric|gte:0',
            'time_min' => 'required|numeric|gte:0',
            'time_max' => 'required|numeric|gte:0',
            'delivery_price' => 'required|numeric|gte:0',
            'sum_for_free' => 'required|numeric|gte:0',
            'coordinates' => 'required|array',
            'coordinates.*.x' => 'required|numeric',
            'coordinates.*.y' => 'required|numeric',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
