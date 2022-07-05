<?php
    namespace App\ReadRepository\Shop;

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
    }