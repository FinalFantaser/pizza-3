<?php

namespace App\Http\Controllers\Api\V1\Home\Shop\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\DeliveryZoneResource;
use App\Services\Shop\Delivery\DeliveryZoneService;
use Illuminate\Http\Request;

class ShowDeliveryZonesController extends Controller
{
    public function __construct(
        private DeliveryZoneService $service
    )
    {} //Конструктор

    public function __invoke(Request $request)
    {
        $request->validate(['city' => 'required|exists:cities,slug']);

        return DeliveryZoneResource::collection(
            $this->service->findByCitySlug(slug: $request->city, with: 'city')
        );
    }
}
