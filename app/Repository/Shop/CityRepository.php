<?php
    namespace App\Repository\Shop;

    use App\Models\Shop\City;
    
    class CityRepository{
        
        public function create(string $name, ?string $address = null, ?string $phone = null): City{
            return City::create([
                'name' => $name,
                'address' => $address,
                'phone' => $phone
            ]);
        } //create

        public function update(City $city, string $name, ?string $address = null, ?string $phone = null): void{
            $city->update([
                'name' => $name,
                'address' => $address,
                'phone' => $phone
            ]);
        } //update

        public function remove(City $city): void{
            $city->delete();
        } //remove
    }