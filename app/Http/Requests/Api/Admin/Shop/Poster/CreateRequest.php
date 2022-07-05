<?php

namespace App\Http\Requests\Api\Admin\Shop\Poster;

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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:posters|string|max:255',
            'description' => 'required|string|max:512',
            'enabled' => 'required|boolean',
            'posterImage' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
        ];
    }
}
