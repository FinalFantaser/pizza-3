<?php
    namespace App\Repository\Shop;

    use Illuminate\Http\Request;

    use App\Models\Shop\City;
    use App\Models\Shop\Product;
    use App\Models\Shop\Category;
    use App\Http\Controllers\Meta;

    use Illuminate\Support\Facades\DB;

    class ProductRepository{
        
        public function create($name, $price, $priceSale, $description, $advice, $tags, $properties, $seo_title, $seo_description, $seo_keywords): Product{
            return Product::create([
                    'name' => $name,
                    'status' => Product::STATUS_DRAFT,
                    'price' => $price,
                    'price_sale' => $priceSale,
                    'description' => $description,
                    'meta' => new Meta($seo_title, $seo_description, $seo_keywords),
                    'tags' => $tags,
                    'properties' => $properties,
            ]);
        } //create

        public function update(Product $product, $name, $price, $priceSale, $description, $advice, $tags, $properties, $seo_title, $seo_description, $seo_keywords){
            $product->update([
                'name' => $name,
                'price' => $price,
                'price_sale' => $priceSale,
                'description' => $description,
                'meta' => new Meta($seo_title, $seo_description, $seo_keywords),
                'tags' => $tags,
                'properties' => $properties,
            ]);

            return $product;
        } //update

        public function updateCategory(Product $product, Category $category){ //Занести продукт в категорию
            
                $product->categories()->detach();
                $product->categories()->attach([$category->id]);
        } //attachCategory

        public function updateImage(Product $product){ //Обновить изображение у продукта
            $product->addMultipleMediaFromRequest(['productImage'])
                    ->each(function ($fileAdder) use ($product) {
                        $fileAdder->toMediaCollection('product');
                    });
        } //updateImage

        public function remove(Product $product): void{
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

        public function updateRecommended(Request $request, Product $product)
        {
            $product->updateRecommended($request->recommended);
        } //updateRecommended

        public function activate(Product $product): void
        {
            $product->activate();
        } //activate

        public function draft(Product $product): void
        {
            $product->draft();
        } //draft

        public function makeAvailable(Product $product): void
        {
            $product->makeAvailable();
        } //makeAvailable

        public function makeUnavailable(Product $product): void
        {
            $product->makeUnavailable();
        } //makeUnavailable

        public function reduceQuantity(Product $product): void
        {
            $product->reduceQuantity();
        } //reduceQuantity

        public function recountRating(Product $product): void
        {
            $product->recountRating();
        } //recountRating
    }