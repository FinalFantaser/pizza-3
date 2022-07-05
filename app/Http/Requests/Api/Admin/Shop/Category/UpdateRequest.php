<?php

namespace App\Http\Requests\Api\Admin\Shop\Category;
use Illuminate\Validation\Rule;
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
            'name' => ['required', 'string', Rule::unique('categories')->ignore($this->category->id)],
            'categoryImage' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
        ];
    }
}
