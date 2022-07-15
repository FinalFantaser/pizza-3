<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;

use App\Http\Resources\CategoryResource;
use App\Services\Shop\CategoryService;
use App\Services\Shop\CityService;
use App\Http\Resources\CityResource;

class ShowCategoriesController extends Controller
{
    public function __construct(private CategoryService $service){}

    public function __invoke(){
        return CategoryResource::collection(
            $this->service->findAll()
        );
    }
}
