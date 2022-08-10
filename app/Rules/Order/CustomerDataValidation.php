<?php

namespace App\Rules\Order;

use App\Models\Shop\City;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CustomerDataValidation implements Rule
{
    //Обязательные поля
    private $required = [
        'name',
        // 'email',
        'phone',
        'city_id',
        'street'
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

        return
            $this->hasRequired($data)
            && $this->notEmpty($data)
            && $this->cityExists($data['city_id'])
            && $this->validatePhone($data['phone'])
            ;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Поле customer_data содержит некорректные данные';
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

    private function validatePhone(string $phone): bool{ //Валидация номера телефона с помощью регулярного выражения
        return preg_match('^((\+7|7|8)+([0-9]){10})$^', $phone); //Регулярное выражение взято из интернета
    } //validatePhone

    private function cityExists(int $id): bool{ //Проверка наличия города в базе данных
        return City::where('id', $id)->exists();
    } //cityExists
}
