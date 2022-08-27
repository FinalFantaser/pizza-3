<?php

namespace App\Http\Requests\Api\Admin\Shop\Jupiter\Create;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'required|string|max:256',
            'price' => 'required|numeric|gte:0',
            'jupiter_id' => 'required|integer',
            'option_id' => 'required|exists:option_records,id',
            'option_selected' => 'required',
        ];
    }
}
