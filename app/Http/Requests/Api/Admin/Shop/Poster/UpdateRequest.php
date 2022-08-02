<?php

namespace App\Http\Requests\Api\Admin\Shop\Poster;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use App\Rules\CitiesExist;

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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('posters')->ignore($this->poster->id)],
            'description' => 'required|string|max:512',
            'anchor' => 'nullable|string|max:1024',
            'enabled' => 'required|in:true,false',
            'posterImage' => 'mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
            'city_id' => ['json', new CitiesExist],
        ];
    } //rules

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
