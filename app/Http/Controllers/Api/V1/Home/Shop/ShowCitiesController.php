<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;

use App\Services\Shop\CityService;
use App\Http\Resources\CityResource;

class ShowCitiesController extends Controller
{
    public function __construct(private CityService $service){}

    public function __invoke(){
        return CityResource::collection(
            $this->service->findAll()
        );
    }
}
