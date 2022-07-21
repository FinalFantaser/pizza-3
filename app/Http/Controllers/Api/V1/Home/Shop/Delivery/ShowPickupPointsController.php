<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\PickupPointResource;
use App\Services\Shop\Delivery\PickupPointService;
use Illuminate\Http\Request;

class ShowPickupPointsController extends Controller
{
    public function __construct(
        private PickupPointService $pickupPointService
    )
    {} //Конструктор

    public function __invoke(Request $request)
    {
        $request->validate(['city_id' => 'required|exists:cities,id'], ['city_id.exists' => 'В базе нет города ' . $request->city_id]);

        return PickupPointResource::collection(
            $this->pickupPointService->findByCity($request->city_id)
        );
    }
}
