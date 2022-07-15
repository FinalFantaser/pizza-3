<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\Product;
    use App\Models\Shop\Category;
    use App\Models\Shop\City;
use Illuminate\Database\Eloquent\ModelNotFoundException;

    class ProductReadRepository{
        public function getMethods()
        {
            $products = Product::orderByDesc('id');
            return $products;
        } //getMethods

        public function findLatest()
        {
            $products = Product::orderByDesc('id');

            return $products;
        } //findLatest

        public function findById(int|array $id, string|array $with = null)
        {
            $product = Product::when(function($query) use ($id){
                    return is_int($id)
                        ? $query->where('id', $id)
                        : $query->whereIn('id', $id);
                })
                ->when(function($query) use ($with){
                    return is_null($with)
                        ? $query
                        : $query->with($with);
                })
                ->get();

            //Проверка, найдены ли модели
            if($product->isEmpty()
                    || (is_array($id) && $product->count() !== count($id))
              )
                throw new ModelNotFoundException;


            return $product;
        } //findById

        public function findByCity(City $city, int $num = 50){
            return $city->products()->paginate($num);
        } //findByCity

        public function findBySlug(string $slug){
            return Product::where('slug', $slug)->first();
        } //findBySlug

        public function findByCityAndCategory(City $city, Category $category, string|array $with = null){
            $products = Product
                ::join('product_city', 'products.id', '=', 'product_city.product_id')
                // ->join('cities', 'cities.id', '=', 'product_city.city_id')
                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->where(['product_city.city_id' => $city->id, 'category_product.category_id' => $category->id])
                ->when(function($query) use ($with) {
                    if(is_null($with))
                        return $query;
                    else
                        return $query->with($with);
                })
                ->get();

            return $products;
        } //findByCityAndCategory

        public function checkIsAvailable(Product $product): bool
        {
            return $product->canBeOrdered();
        } //checkIsAvailable
    }
