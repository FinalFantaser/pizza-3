<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\City;
    use App\Models\Shop\Poster;

    class PosterReadRepository{
        public function getMethods(){
            return Poster::orderByDesc('id');
        } //findAll

        public function findById(int $id){
            return Poster::findOrFail($id);
        } //findById

        public function findEnabled(){
            return Poster::where('enabled', 1)->orderByDesc('id')->get();
        } //findActive

        public function findByCity(City $city){
            $posters = Poster
                ::join('posters_cities', 'posters.id', '=', 'posters_cities.poster_id')
                ->where(['posters_cities.city_id' => $city->id])
                ->active()
                ->get();

            return $posters;
        } //findByCityAndCategory
    }
