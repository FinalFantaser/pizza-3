<?php

namespace App\Rules\Order;

use App\Models\Shop\Option\OptionRecord;
use App\Models\Shop\Option\OptionType;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OptionsValidation implements Rule
{
    //Обязательные поля
    private $required = ['id', 'selected'];

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

        foreach($data as $orderItem){
            if(!Arr::has($orderItem, 'options')) //Если опций нет, ничего не проверять
                continue;

            $options = $orderItem['options'];
            if( !$this->itemOptionsAreValid($options))
                return false;
        }

        return true;
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'поле options содержит некорректные данные';
    }

    //
    //  Методы для проверки полей
    //
    private function itemOptionsAreValid(array $options): bool{ //Метод, объединяющий все методы валидации элемента
        return 
            $this->isArray($options)
            && $this->optionsHaveRequired($options)
            && $this->selectedIsArray($options)
            && $this->optionsNotEmpty($options)
            && $this->optionsExist($options)
            && $this->selectedQtyIsValid($options)
            && $this->selectedExist($options); 
    } //itemOptionsAreValid

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

    private function optionsHaveRequired(array $options): bool{ //Проверяет весь массив на наличие обязательных полей
        foreach($options as $option)
            if(!$this->hasRequired($option))
                return false;
        return true;
    } //optionsHaveRequired

    private function selectedIsArray(array $options){ //Проверяет, является ли selected массивом
        foreach($options as $option)
            if(!is_array($option['selected']))
                return false;
        
        return true;
    } //selectedIsArray

    private function notEmpty(array $data): bool{ //Проверяет, чтобы переданные параметры не были пустыми
        return 
            !Str::of($data['id'])->isEmpty() && count($data['selected']) > 0;
        ;
    } //notEmpty

    private function optionsNotEmpty(array $options): bool{ //Проверяет весь массив на пустые поля
        foreach($options as $option)
            if(!$this->notEmpty($option))
                return false;
        return true;
    } //optionsNotEmpty

    private function optionsExist($options): bool{ //Проверяет, существуют ли указанные опции
        $ids = Arr::pluck($options, 'id');
        return
            OptionRecord::whereIn('id', $ids)->count() === count($ids);
    } //optionsExist

    private function selectedQtyIsValid($options): bool{ //Проверяет, допускает ли опция выбор сразу нескольких вариантов
        $optionRecords = OptionRecord::whereIn('id', Arr::pluck($options, 'id'))->with(['option', 'option.type'])->get();
        
        foreach($options as $option)
            if( count($option['selected']) > 1 && !$optionRecords->where('id', $option['id'])->first()->typeIs(OptionType::TYPE_CHECKBOX) )
                return false;

        return true;
    } //selectedQtyIsValid

    private function selectedExist($options): bool{ //Проверяет, существуют ли выбранные варианты в опциях
        $optionRecords = OptionRecord::whereIn('id', Arr::pluck($options, 'id'))->get();

        foreach($options as $option){
            $items = collect($optionRecords->where('id', $option['id'])->first()->items);
            if($items->whereIn('name', $option['selected'])->count() < 1)
                return false;
        }

        return true;
    } //selectedExist
}
