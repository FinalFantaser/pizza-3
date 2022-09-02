<?php

namespace App\Http\Requests\Api\Home\Shop\Order;

use App\Models\Shop\Delivery\DeliveryMethod;
use App\Rules\Order\CompliesWithDelivery;
use App\Rules\Order\CompliesWithDeliveryZone;
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
            // 'customer_data' => ['required', 'json', new CustomerDataValidation],
            // 'delivery_method_id' => ['required', 'exists:delivery_methods,id'],
            'delivery_method' => ['required', Rule::in([DeliveryMethod::TYPE_COURIER, DeliveryMethod::TYPE_PICKUP])],
            'delivery_zone_id' => ['required', 'exists:delivery_zones,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id', new CompliesWithDelivery ],
            'time' => ['nullable', 'string', 'max:255'],
            // 'pickup_point_id' => [Rule::requiredIf(DeliveryMethod::findOrFail($this->delivery_method_id)->type === DeliveryMethod::TYPE_PICKUP), 'exists:pickup_points,id'],
        'order_items' => ['required', 'json', new OrderItemsValidation, /*new OptionsValidation*/],
            'note' => 'nullable|string|max:255',

            // TODO Протестировать валидацию и переделать передачу данных под исправленную версию:
            // Данные заказчика
            'customer_data' => 'required|array',
            'customer_data.name' => 'required|string|max:255',
            'customer_data.phone' => ['required', 'regex:^((\+7|7|8)+([0-9]){10})$^'],
            'customer_data.city_id' => ['required', 'exists:cities,id', new CompliesWithDeliveryZone], //TODO Проверить, чтобы город соотносился с зоной доставки
            'customer_data.actual_city' => 'nullable|string|max:255',
            'customer_data.street' => 'nullable|string|max:255',
            'customer_data.house' => 'nullable|string|max:255',
            'customer_data.room' => 'nullable|int|gte:0',
            'customer_data.entrance' => 'nullable|int|gte:0',
            'customer_data.intercom' => 'nullable|string|max:255',
            'customer_data.floor' => 'nullable|int|gte:1',
            'customer_data.corp' => 'nullable|string|max:255',

            //Данные заказа
            // 'order_items' => 'required|array',
            // 'order_items.*.product_id' => 'required|exists:products,id',
            // 'order_items.*.product_quantity' => 'required|int|gte:1',
            // 'order_items.*.options' => 'array',
            
            //Опции заказа
            // 'order_items.*.options.*.id' => 'required|exists:option_records,id',
            // 'order_items.*.options.*.selected' => 'required|array',
            //TODO Сделать проверку существования выбранных имён опций и возможности выбора нескольких вариантов
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = new Response(['error' => $validator->errors()->first()], 422);
        throw new ValidationException($validator, $response);
    }
}
