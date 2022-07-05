<?php

namespace App\Http\Requests\Api\Admin\Manager;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'city_id' => 'required|exists:cities,id'
        ];
    }

    public function messages(){
        return [
            'user_id.required' => 'Необходимо указать id пользователя',
            'user_id.exists' => 'Id пользователя отсутствует в базе данных',
            'city_id.required' => 'Необходимо указать id города',
            'city_id.exists' => 'Id города отсутствует в базе данных',
        ];
    }
}
