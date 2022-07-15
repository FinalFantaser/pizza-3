<?php

namespace App\Http\Controllers\Api\V1\Home\Shop;

use App\Http\Controllers\Controller;

use App\Http\Resources\PosterResource;
use App\Services\Shop\CityService;
use App\Services\Shop\PosterService;
use Illuminate\Http\Request;

class ShowPostersController extends Controller
{
    public function __construct(
        private PosterService  $posterService,
        private CityService $cityService
    ){}

    public function __invoke(Request $request){
        $request->validate(['city' => 'required|exists:cities,slug',], ['city.exists' => "В базе нет города $request->city ."]);
        $city = $this->cityService->findBySlug($request->city);

        return PosterResource::collection(
            $this->posterService->findByCity(
                city: $city
            )
        );
    }
}
