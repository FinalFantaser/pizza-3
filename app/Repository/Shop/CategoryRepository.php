<?php
    namespace App\Repository\Shop;

    use App\Models\Shop\Category;
    use App\Http\Controllers\Meta;
    use Illuminate\Support\Facades\DB;

    class CategoryRepository{

        public function create($name, $seo_title, $seo_description, $seo_keywords){
            $category = Category::create([
                'name' => $name,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords)
            ]);

            $this->updateImage($category);

            return $category;
        } //create

        public function update(Category $category, $name, $seo_title, $seo_description, $seo_keywords){
            $category->update([
                'name' => $name,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords)
            ]);

            $this->updateImage($category);

            return $category;
        } //update

        public function detachAllProducts($category){ //Открепить все продукты от категории
            DB::table('category_product')->where(['category_id' => $category->id])->delete();
        } //detachAllProducts

        public function updateImage(Category $category){
            if(request()->hasFile('categoryImage')) {
                $category->clearMediaCollection('categories');
                $category->addMediaFromRequest('categoryImage')->toMediaCollection('categories');
            }
        } //updateImage

        public function remove(Category $category): void{
            $category->clearMediaCollection('categories');
            $this->detachAllProducts($category);
            $category->delete();
        } //remove
    }
