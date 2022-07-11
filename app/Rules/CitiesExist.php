<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Shop\City;

class CitiesExist implements Rule
{
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
        $cities = json_decode($value);
        if(is_integer($cities))
            return City::where('id', $cities)->exists();
        elseif(is_array($cities)){
            if(count($cities) < 1)
                return true;

            foreach($cities as $city)
                if(!City::where('id', $city)->exists())
                    return false;
            
            return true;
        }
        else
            return false; 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Указанные города отсутствуют в базе данных';
    }
}
