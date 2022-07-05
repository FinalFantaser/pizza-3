<?php
    namespace App\ReadRepository\Shop;

    use App\Models\Shop\Product;
    use App\Models\Shop\Category;

    class ProductReadRepository{
        public function getMethods()
        {
            $products = Product::orderByDesc('id');
            return $products;
        }

        public function findLatest()
        {
            $products = Product::orderByDesc('id')->active();

            return $products;
        }

        public function findById(int $id)
        {
            $product = Product::where('id', $id)->active()->first();
            return $product;
        }

        public function findByCity(City $city){
            return $city->products;
        }

        public function checkIsAvailable(Product $product): bool
        {
            return $product->canBeOrdered();
        }
    }