<?php

namespace App\Http\Requests\Api\Home\Shop\Order;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Rules\Order\CustomerDataValidation;
use App\Rules\Order\OptionsValidation;
use App\Rules\Order\OrderItemsValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutRequest extends FormRequest
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
            "customer_data" => ['required', 'json', new CustomerDataValidation],
            "payment_method_id" => ['required', 'exists:payment_methods,id', /*Проверка соответствия методу доставки*/ ],
            "delivery_method_id" => ['required', 'exists:delivery_methods,id'],
            "time" => ['nullable', 'string', 'max:255'],
            "pickup_point_id" => [Rule::requiredIf(DeliveryMethod::findOrFail($this->delivery_method_id)->type === DeliveryMethod::TYPE_PICKUP), 'exists:pickup_points,id'],
            "order_items" => ['required', 'json', new OrderItemsValidation, new OptionsValidation],
            "note" => 'nullable|string|max:255',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
