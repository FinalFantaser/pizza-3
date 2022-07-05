<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\City;

    class CityReadRepository{
        public function getMethods(){
            $methods = City::orderBy('name');
            return $methods;
        } //getMethods

        public function findById($id){
            return City::findOrFail($id);
        } //findById
    }