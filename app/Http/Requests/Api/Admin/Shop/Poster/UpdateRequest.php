<?php

namespace App\Http\Requests\Api\Admin\Shop\Poster;
use Illuminate\Contracts\Validation\Validator;
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', Rule::unique('posters')->ignore($this->poster->id)],
            'description' => 'required|string|max:512',
            'enabled' => 'required|boolean',
            'posterImage' => 'mimes:jpg,jpeg,png,gif,svg,webp|max:2048',
        ];
    } //rules

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        $response = response()->json([
            'message' => 'Invalid data send',
            'details' => $errors->messages(),
        ], 422);

        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}
