<?php

namespace App\Http\Requests\Api\Admin\Shop\Poster;

use Illuminate\Foundation\Http\FormRequest;

class AttachToCityRequest extends FormRequest
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
            'poster_id' => 'required|exists:posters,id',
            'city_id' => 'required|exists:cities,id',
        ];
    }
}
