<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\Category;

    class CategoryReadRepository{
        public function getMethods(){
            return Category::orderBy('name');
        } //getMethods

        public function findById(int $id){
            return Category::findOrFail($id);
        } //findById

        public function findBySlug(string $slug){
            return Category::where('slug', $slug)->first();
        } //findByCity
    }