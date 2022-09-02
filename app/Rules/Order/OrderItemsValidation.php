<?php

namespace App\Rules\Order;

use App\Models\Shop\Product;
use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderItemsValidation implements Rule
{
    //Обязательные поля
    private $required = [
        'product_id', 'product_quantity'
    ];

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = json_decode(json: $value, associative: true);

        return $this->isArray($data)
            && $this->itemsHaveRequired($data)
            && $this->itemsNotEmpty($data)
            && $this->productQtyIsValid($data)
            && $this->productsExist($data);
            ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Поле order_items содержит некорректную информацию';
    }

    //
    //  Методы для проверки полей
    //
    private function isArray(array $data): bool{ //Проверяет, чтобы order_items был массивом и не был пустым
        return is_array($data) && count($data) > 0;
    } //isArray

    private function hasRequired(array $data): bool{ //Проверяет, присутствуют ли все поля в запросе
        return
            Arr::has(
                array: $data,
                keys: $this->required
            );
    } //hasRequired

    private function itemsHaveRequired(array $items): bool{ //Проверяет весь массив на наличие обязательных полей
        foreach($items as $item)
            if(!$this->hasRequired($item))
                return false;
        return true;
    } //itemsHaveRequired

    private function notEmpty(array $data): bool{ //Проверяет, чтобы переданные параметры не были пустыми
        foreach($this->required as $field)
            if (Str::of($data[$field])->isEmpty())
                return false;
        return true;
    } //notEmpty

    private function itemsNotEmpty(array $items): bool{ //Проверяет весь массив на пустые поля
        foreach($items as $item)
            if(!$this->notEmpty($item))
                return false;
        return true;
    } //itemsNotEmpty

    private function productQtyIsValid(array $data){ //Проверка количества продуктов
        foreach($data as $item)
            if( intval($item['product_quantity']) < 1)
                return false;
        return true;
    } //productQtyIsValid

    private function productsExist(array $data): bool{ //Проверка существования продуктов
        $ids = Arr::pluck($data, 'product_id');

        foreach($ids as $id)
            if(! Product::where('id', $id)->exists() )
                return false;

        return true;
    } //productsExist
}
