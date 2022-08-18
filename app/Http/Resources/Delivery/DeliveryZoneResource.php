<?php

namespace App\Http\Resources\Delivery;

use App\Http\Resources\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryZoneResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'city_id' => $this->city_id,
            'city' => new CityResource( $this->whenLoaded('city') ),
            'restaurant_id' => $this->restaurant_id,
            'code' => $this->code,
            'sum_min' => $this->sum_min,
            'time_min' => $this->time_min,
            'time_max' => $this->time_max,
            'delivery_price' => $this->delivery_price,
            'sum_for_free' => $this->sum_for_free,
            'coordinates' => $this->coordinates,
        ];
        
    }
}
