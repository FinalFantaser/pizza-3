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
                    'status' => Product::STATUS_ACTIVE,
                    'price' => $price,
                    'price_sale' => $priceSale,
                    'description' => $description,
                    'meta' => new Meta($seo_title, $seo_description, $seo_keywords),
                    'tags' => $tags,
                    'properties' => $properties,
            ]);

            //Назначение категории
            $this->updateCategory($product, Category::find($category_id));

            //Загрузка картинки
            $this->updateImage($product);

            //Привязка к городам
            $this->attachToCity($product, $city_id);

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

            //Загрузка картинки
            $this->updateImage($product);

            //Привязка к городам
            $this->attachToCity($product, $city_id);

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
        } //attachCategory

        public function updateImage(Product $product){ //Обновить изображение у продукта
            $product->clearMediaCollection('products');
            if(request()->hasFile('productImage') )
                $product->addMediaFromRequest('productImage')->toMediaCollection('products');
        } //updateImage

        public function remove(Product $product): void{
            $product->clearMediaCollection('products'); //Удаление картинки
            $this->deleteCategory($product); //Удаление из категории
            $this->clearAllCities($product); //Отвязка от всех городов

            $product->delete();
        } //remove

        public function attachToCity(Product $product, null|int|string $city){  
            if(is_string($city)){
                $ids = json_decode($city);
            
                if(is_int($ids)){
                    DB::table('product_city')->updateOrInsert(['product_id' => $product->id, 'city_id' => $city]);
                    return;
                }

                if(count($ids) < 1)
                    return;

                //Создание массива
                $data = array_map(function($value) use ($product) {
                    return [
                        'product_id' => $product->id,
                        'city_id' => $value
                    ];
                }, $ids);
                
                foreach($data as $item)
                    DB::table('product_city')->updateOrInsert($item);
            }
            elseif(is_int($city))
                DB::table('product_city')->updateOrInsert(['product_id' => $product->id, 'city_id' => $city]);
        } //attachToCity

        public function detachFromCity(Product $product, null|int|string $city){
            if(is_int($city))
                DB::table('product_city')->where(['product_id' => $product->id, 'city_id' => $city])->delete();
            elseif(is_string($city)){
                $ids = json_decode($city);

                if(is_int($ids)){
                    DB::table('product_city')->where(['product_id' => $product->id, 'city_id' => $ids])->delete();
                    return;
                }

                DB::table('product_city')->where('product_id', $product->id)->whereIn('city_id', $ids)->delete();
            }
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