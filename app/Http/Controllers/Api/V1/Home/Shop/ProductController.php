<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\City;
use App\Models\Shop\Category;
use App\Models\Shop\Product;

use App\Services\Shop\ProductService;
use App\Http\Resources\ProductResource;

use App\Services\Shop\CityService;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private CityService $cityService
    ){} //Конструктор

    public function index(Request $request, Category $category){
        $city = $this->cityService->findById($request->city_id);

        return ProductResource::collection(
            $this->productService->findByCityAndCategory($city, $category)
        );

        return response($city->name, 200);
    } //index

    public function show(City $city, category $category, Product $product){
        return new ProductResource(
            $product->load('category')
        );
    }
}
