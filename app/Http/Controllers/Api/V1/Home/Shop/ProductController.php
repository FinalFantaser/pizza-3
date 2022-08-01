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
        // $user = \App\Models\User::find(1);
        // return response($user->createToken('token-name')->plainTextToken);

        $request->validate(['city' => 'required|exists:cities,slug',], ['city.exists' => "В базе нет города $request->city ."]);

        $city = $this->cityService->findBySlug($request->city);


        return ProductResource::collection(
            $this->productService->findByCityAndCategory(
                city: $city,
                category: $category,
                with: ['categories:id,name'],
                status: Product::STATUS_ACTIVE
            )
        );
    } //index

    public function show(Request $request, Product $product){
        return new ProductResource(
            $product->load('categories:slug,name', 'cities:slug,name')
        );
    } //show

    public function recommended(){
        return ProductResource::collection(
            $this->productService->findRecommended()
        );
    } //recommended
}
