<?php
    namespace App\Repository\Shop;

    use App\Models\Shop\City;
    
    class CityRepository{
        
        public function create(string $name): City{
            return City::create(['name' => $name]);
        } //create

        public function update(City $city, string $name): void{
            $city->update(['name' => $name]);
        } //update

        public function remove(City $city): void{
            $city->delete();
        } //remove
    }