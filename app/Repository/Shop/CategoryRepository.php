<?php
    namespace App\Repository\Shop;

    use App\Models\Shop\Category;
    use App\Http\Controllers\Meta;

    class CategoryRepository{
        
        public function create($name, $seo_title, $seo_description, $seo_keywords){
            $category = Category::create([
                'name' => $name,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords)
            ]);

            return $category;
        } //create

        public function update(Category $category, $name, $seo_title, $seo_description, $seo_keywords){
            $category->update([
                'name' => $name,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords)
            ]);

            return $category;
        } //update

        public function updateImage(Category $category){
            if ($category->getFirstMedia('categoryImages'))
                $category->getFirstMedia('categoryImages')->delete();
            
            $category->addMediaFromRequest('categoryImage')->toMediaCollection('categoryImages');
        } //updateImage

        public function remove(Category $category): void{
            $category->delete();
        } //remove
    }