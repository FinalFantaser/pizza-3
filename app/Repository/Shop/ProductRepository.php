<?php
    namespace App\Repository\Shop;

    use Illuminate\Http\Request;

    use App\Models\Shop\City;
    use App\Models\Shop\Product;
    use App\Models\Shop\Category;
    use App\Http\Controllers\Meta;

    use Illuminate\Support\Facades\DB;

    class ProductRepository{
        
        public function create($name, $price, $priceSale, $description, $tags, $properties, $seo_title, $seo_description, $seo_keywords, $category_id, $city_id): Product{
            $product = Product::create([
                    'name' => $name,
                    'status' => Product::STATUS_DRAFT,
                    'price' => $price,
                    'price_sale' => $priceSale,
                    'description' => $description,
                    'meta' => new Meta($seo_title, $seo_description, $seo_keywords),
                    'tags' => $tags,
                    'properties' => $properties,
            ]);

            //Загрузка картинки
            $this->updateImage($product);

            //Назначение категории
            $this->updateCategory($product, Category::find($category_id));

            //Привязка к городу
            if(!is_null($city_id))
                $this->attachToCity($product, City::find($city_id));

            return $product;
        } //create

        public function update(Product $product, $name, $price, $priceSale, $description, $tags, $properties, $seo_title, $seo_description, $seo_keywords, $category_id, $city_id){
            $product->update([
                'name' => $name,
                'price' => $price,
                'price_sale' => $priceSale,
                'description' => $description,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords),
                'tags' => $tags,
                'properties' => $properties,
            ]);

            //Назначение категории
            $this->updateCategory($product, Category::find($category_id));

            //Привязка к городу
            if(!is_null($city_id))
                $this->attachToCity($product, City::find($city_id));

            //Загрузка картинки
            $this->updateImage($product);

            return $product;
        } //update

        public function deleteCategory(Product $product){ //Удалить продукт из категории
            // DB::table('category_product')->where(['product_id' => $product->id, 'category_id' => $category->id])->delete();
            DB::table('category_product')->where(['product_id' => $product->id])->delete();
        } //deleteCategory

        public function updateCategory(Product $product, Category $category){ //Занести продукт в категорию
            //Пока что сделано так, чтобы продукт можно было занести только в одну категорию
            $this->deleteCategory($product);
            DB::table('category_product')->updateOrInsert(['product_id' => $product->id], ['category_id' => $category->id]);

            // $product->categories()->detach();
            // $product->categories()->attach([$category->id]);
        } //attachCategory

        public function deleteImage(Product $product){
            if($product->getFirstMedia('product'))
                $product->getFirstMedia(('product'))->delete();
        }

        public function updateImage(Product $product){ //Обновить изображение у продукта
            // $product->addMultipleMediaFromRequest(['productImage'])
            //         ->each(function ($fileAdder) use ($product) {
            //             $fileAdder->toMediaCollection('product');
            //         });

            $this->deleteImage($product);
            if(request()->hasFile('productImage') )
                $product->addMediaFromRequest('productImage')->toMediaCollection('product');
        } //updateImage

        public function remove(Product $product): void{
            $this->deleteImage($product); //Удаление картинки
            $this->deleteCategory($product); //Удаление из категории
            $this->clearAllCities($product); //Отвязка от всех городов

            $product->delete();
        } //remove

        public function attachToCity(Product $product, City $city){           
            DB::table('product_city')->updateOrInsert(['product_id' => $product->id, 'city_id' => $city->id]);
        } //attachToCity

        public function detachFromCity(Product $product, City $city){
            DB::table('product_city')->where(['product_id' => $product->id, 'city_id' => $city->id])->delete();
        } //detachFromCity

        public function clearAllCities(Product $product){
            DB::table('product_city')->where(['product_id' => $product->id])->delete();
        } //clearAllCities

        public function activate(Product $product): void
        {
            $product->activate();
        } //activate

        public function draft(Product $product): void
        {
            $product->draft();
        } //draft

        public function reduceQuantity(Product $product): void
        {
            $product->reduceQuantity();
        } //reduceQuantity

        public function recountRating(Product $product): void
        {
            $product->recountRating();
        } //recountRating
    }