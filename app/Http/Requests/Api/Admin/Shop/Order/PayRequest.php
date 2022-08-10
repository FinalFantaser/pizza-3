<?php

namespace App\Http\Requests\Api\Admin\Shop\Order;

use App\Models\Shop\Payments\Record;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
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
            'order_id' => 'required|exists:orders,id',
            'code' => 'required|exists:payment_methods,code',
            'change_sum' => 'nullable|numeric|gte:0',
            'payer' => ['required', 'string', Rule::in([Record::PAYER_ADMIN, Record::PAYER_CUSTOMER]), ],
        ];
    }
}
