<?php

namespace App\Http\Requests\Api\Admin\Shop\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Rules\CitiesExist;

class ProductRequest extends FormRequest
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
            'name' => 'required',
            'slug' => 'unique:products,slug,' . (optional($this->products)->id ?: 'NULL'),
            'price' => 'required|numeric',
            'price_sale' => 'nullable|numeric',
            'description' => 'nullable|string',
            // 'productImage.*' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'productImage.*' => 'mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'tags' => 'nullable',
            'properties.*' => 'nullable|integer',
//            'properties.weight' => 'required_without:properties.pieces|integer|nullable',
//            'properties.pieces' => 'required_without:properties.weight|integer|nullable',
//            'properties.structure' => 'required|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'seo_keywords' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            // 'city_id' => 'exists:cities,id',
            'city_id' => ['json', new CitiesExist],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
