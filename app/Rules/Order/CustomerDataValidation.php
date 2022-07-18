<?php

namespace App\Rules\Order;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CustomerDataValidation implements Rule
{
    //Обязательные поля
    private $required = [
        'name', 'email', 'phone', 'cit_id', 'address'
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
        $data = json_decode(json: $value);

        return $this->hasRequired($data)
            && $this->notEmpty($data)
            && $this->cityExists($data->city_id)
            && $this->validateEmail($data->email)
            && $this->validatePhone($data->phone);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }

    //
    //  Методы для проверки полей
    //
    private function hasRequired(array $data): bool{ //Проверяет, присутствуют ли все поля в запросе
        return
            Arr::has(
                array: $data,
                keys: $this->required
            );
    } //hasRequired

    private function notEmpty(array $data): bool{ //Проверяет, чтобы переданные параметры не были пустыми
        foreach($data as $key => $value)
            if (Str::of($value)->isEmpty())
                return false;

        return true;
    } //notEmpty

    private function validateEmail(string $email): bool{ //Валидация email с помощью регулярного выражения
        // ...
        return true;
    } //validateEmail

    private function validatePhone(string $email): bool{ //Валидация номера телефона с помощью регулярного выражения
        //...
        return true;
    } //validatePhone

    private function cityExists(int $id): bool{ //Проверка наличия города в базе данных

        return true;
    } //cityExists
}
