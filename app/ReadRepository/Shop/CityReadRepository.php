<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\City;

    class CityReadRepository{
        public function getMethods(){
            $methods = City::orderBy('name');
            return $methods;
        } //getMethods

        public function findAll(){ //Найти ВСЕ записи БЕЗ пагинации
            return City::orderBy('name')->get();
        } //findAll

        public function findById(int|string $id){
            return City::findOrFail($id);
        } //findById

        public function findBySlug(string $slug){
            return City::where('slug', $slug)->first();
        } //findBySlug
    }