<?php

namespace App\Http\Resources\Delivery;

use App\Http\Resources\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryZoneResourceShort extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'name' => $this->name,
            'city_id' => $this->city_id,
            'city' => new CityResource( $this->whenLoaded('city') ),
            'restaurant_id' => $this->restaurant_id,
            'code' => $this->code,
        ];
    }
}
