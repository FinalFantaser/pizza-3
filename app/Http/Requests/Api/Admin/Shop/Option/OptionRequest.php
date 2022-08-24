<?php

namespace App\Http\Requests\Api\Admin\Shop\Option;

use App\Models\Shop\Option\Option;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OptionRequest extends FormRequest
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
            'name' => 'required|string|max:128',
            'checkout_type' => [ 'required', 'string', Rule::in([Option::TYPE_SIZE, Option::TYPE_ADDITIONAL, Option::TYPE_OTHER]) ],
            'type_id' => 'required|exists:option_types,id',
            'items' => 'required|json',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
