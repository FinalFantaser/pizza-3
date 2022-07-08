<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\Product;
    use App\Models\Shop\Category;
    use App\Models\Shop\City;

    class ProductReadRepository{
        public function getMethods()
        {
            $products = Product::orderByDesc('id');
            return $products;
        } //getMethods

        public function findLatest()
        {
            $products = Product::orderByDesc('id')->active();

            return $products;
        } //findLatest

        public function findById(int $id)
        {
            $product = Product::where('id', $id)->active()->first();
            return $product;
        } //findById

        public function findByCity(City $city, int $num = 50){
            return $city->products()->paginate($num);
        } //findByCity

        public function findByCityAndCategory(City $city, Category $category){
            return $city->products()->get();
        } //findByCityAndCategory

        public function checkIsAvailable(Product $product): bool
        {
            return $product->canBeOrdered();
        } //checkIsAvailable
    }