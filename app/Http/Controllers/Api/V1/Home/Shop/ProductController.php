<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;

use App\Models\Shop\City;
use App\Models\Shop\Product;

use App\Services\Shop\ProductService;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $service
    ){} //Конструктор

    public function index(){

    } //index
}
