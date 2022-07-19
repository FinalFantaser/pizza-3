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
        'name', 'email', 'phone', 'city_id', 'address'
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
            && $this->validateEmail($data['email'])
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

    private function validateEmail(string $email): bool{ //Валидация email с помощью регулярного выражения
        return preg_match( //Регулярное выражение взято из интернета
            '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',
            $email
        );
        return true;
    } //validateEmail

    private function validatePhone(string $phone): bool{ //Валидация номера телефона с помощью регулярного выражения
        return preg_match('^((\+7|7|8)+([0-9]){10})$^', $phone); //Регулярное выражение взято из интернета
    } //validatePhone

    private function cityExists(int $id): bool{ //Проверка наличия города в базе данных
        return City::where('id', $id)->exists();
    } //cityExists
}
